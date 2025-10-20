<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    const IMPORT_BATCH_SIZE = 10; // Updated batch size as per requirement
    const EXPORT_BATCH_SIZE = 10;
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
}
