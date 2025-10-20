<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Company extends Model
{
    use HasFactory;
    const IMPORT_BATCH_SIZE = 100; // Updated batch size as per requirement
    const EXPORT_BATCH_SIZE = 100;
    const PER_PAGE = 25;
    const FILTER_DUPLICATES = 'duplicates';
    const FILTER_UNIQUE = 'unique';

    protected $fillable = [
        'company_name',
        'email',
        'phone_number',
        'is_duplicate',
    ];

    protected $casts = [
        'is_duplicate' => 'boolean',
    ];

    public function scopeApplyFilters(Builder $query, Request $request): Builder
    {
        return $query
            ->when($request->has('filter'), function ($q) use ($request) {
                $filter = $request->input('filter');

                if ($filter === self::FILTER_DUPLICATES) {
                    $q->where('is_duplicate', true);
                } elseif ($filter === self::FILTER_UNIQUE) {
                    $q->where('is_duplicate', false);
                }
            })
            ->when($request->filled('company_name'), function ($q) use ($request) {
                $q->where('company_name', 'like', '%' . $request->input('company_name') . '%');
            });
    }
}
