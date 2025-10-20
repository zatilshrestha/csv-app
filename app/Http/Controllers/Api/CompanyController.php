<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyExportRequest;
use App\Http\Requests\CompanyImportRequest;
use App\Http\Resources\CompanyResource;
use App\Jobs\CompanyBatchImportJob;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CompanyController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $companies = Company::applyFilters($request)
            ->latest()
            ->paginate($request->get('per_page', Company::PER_PAGE));

        return CompanyResource::collection($companies);
    }

    public function export(CompanyExportRequest $request): StreamedResponse
    {
        $fileName = 'companies_export_' . now()->format('Y_m_d_His') . '.csv';

        $query = Company::applyFilters($request);

        $response = new StreamedResponse(function () use ($query) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Company Name', 'Email', 'Phone Number', 'Is Duplicate']);

            $query->chunk(Company::EXPORT_BATCH_SIZE, function ($companies) use ($file) {
                foreach ($companies as $company) {
                    fputcsv($file, [
                        $company->company_name,
                        $company->email,
                        $company->phone_number,
                        $company->is_duplicate ? 'Yes' : 'No',
                    ]);
                }
            });

            fclose($file);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename={$fileName}");

        return $response;
    }

    public function import(CompanyImportRequest $request): JsonResponse
    {
        $uploadedFile = $request->file('file');

        if (!$uploadedFile || !$uploadedFile->isValid()) {
            return response()->json(['message' => 'Invalid or missing file.'], 422);
        }

        $filePath = $uploadedFile->getRealPath();

        if ($filePath === false) {
            return response()->json(['message' => 'Unable to read uploaded file.'], 422);
        }

        $file = new \SplFileObject($filePath);
        $file->setFlags(\SplFileObject::READ_CSV);
        $file->setCsvControl(',');

        $header = [];
        $batch = [];

        foreach ($file as $index => $row) {
            if (empty($row) || count($row) < 3) continue;

            if ($index === 0) {
                $header = array_map(fn($h) => str_replace(' ', '_', strtolower(trim($h))), $row);
                continue;
            }

            $batch[] = array_combine($header, $row);

            if (count($batch) === Company::IMPORT_BATCH_SIZE) {
                CompanyBatchImportJob::dispatch($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            CompanyBatchImportJob::dispatch($batch);
        }

        return response()->json(['message' => 'CSV file has been queued for processing.']);
    }
}
