<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyExportRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
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
}
