<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyExportRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Response;
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
        $query = Company::applyFilters($request);

        $headers = ['company_name', 'email', 'phone_number', 'is_duplicate'];

        $callback = function () use ($query, $headers) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $headers);

            $query->chunk(Company::EXPORT_BATCH_SIZE, function ($companies) use ($file, $headers) {
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
        };

        return Response::stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=companies.csv",
        ]);
    }
}
