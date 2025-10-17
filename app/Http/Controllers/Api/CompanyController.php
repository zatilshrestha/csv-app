<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CompanyController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $query = Company::query()
            ->when($request->has('filter'), function ($q) use ($request) {
                if ($request->input('filter') === Company::FILTER_DUPLICATES) {
                    $q->where('is_duplicate', true);
                } elseif ($request->input('filter') === Company::FILTER_UNIQUE) {
                    $q->where('is_duplicate', false);
                }
            })
            ->when($request->has('company_name'), function ($q) use ($request) {
                $q->where('company_name', 'like', '%' . $request->input('company_name') . '%');
            });

        $perPage = $request->get('per_page', Company::PER_PAGE);
        $companies = $query->latest()->paginate($perPage);

        return CompanyResource::collection($companies);
    }
}
