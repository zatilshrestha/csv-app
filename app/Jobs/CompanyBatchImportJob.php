<?php

namespace App\Jobs;

use App\Models\Company;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CompanyBatchImportJob implements ShouldQueue
{
    use Queueable;

    protected array $batch;

    /**
     * Create a new job instance.
     */
    public function __construct(array $batch)
    {
        $this->batch = $batch;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $validData = [];

        foreach ($this->batch as $row) {
            $data = [
                'company_name' => $row['company_name'] ?? null,
                'email' => $row['email'] ?? null,
                'phone_number' => $row['phone_number'] ?? null,
            ];

            $validator = Validator::make($data, [
                'company_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone_number' => 'required|string|max:20',
            ]);

            if ($validator->fails()) {
                Log::warning('Invalid data and import skipped:', [
                    'data' => $data,
                    'errors' => $validator->errors()->all(),
                ]);

                continue;
            }

            $validatedData = $validator->validated();

            $companyExists = Company::where('company_name', $validatedData['company_name'])
                ->where('email', $validatedData['email'])
                ->where('phone_number', $validatedData['phone_number'])
                ->exists();

            $validatedData['is_duplicate'] = (bool)$companyExists;
            $validatedData['created_at'] = now();
            $validatedData['updated_at'] = now();

            $validData[] = $validatedData;
        }

        if (!empty($validData)) {
            Company::insert($validData);
        }
    }
}
