<?php

namespace Tests\Feature\Company;

use App\Jobs\CompanyBatchImportJob;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportCsvTest extends TestCase
{
    use RefreshDatabase;

    public function test_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Company::factory()->create([
            'company_name' => 'Zero Corp',
            'email' => 'info@zerocorp.com',
            'phone_number' => '9800000000',
            'is_duplicate' => false,
        ]);

        $rows = [
            [
                'company_name' => 'Zero Corp',
                'email' => 'info@zerocorp.com',
                'phone_number' => '9800000000',
            ],
            [
                'company_name' => 'New Co',
                'email' => 'new@co.com',
                'phone_number' => '0987654321',
            ],
            [
                'company_name' => 'Bad Tech',
                'email' => 'not-an-email',
                'phone_number' => '1234567890',
            ],
            [
                'company_name' => 'Bad Co',
                'email' => 'bad@email.com',
                'phone_number' => null,
            ],
        ];

        CompanyBatchImportJob::dispatchSync($rows);

        $this->assertDatabaseCount('companies', 3);
        $this->assertDatabaseHas('companies', [
            'company_name' => 'Zero Corp',
            'email' => 'info@zerocorp.com',
            'phone_number' => '9800000000',
            'is_duplicate' => false,
        ]);
        $this->assertDatabaseHas('companies', [
            'company_name' => 'New Co',
            'email' => 'new@co.com',
            'phone_number' => '0987654321',
            'is_duplicate' => false,
        ]);
        $this->assertDatabaseHas('companies', [
            'company_name' => 'Zero Corp',
            'email' => 'info@zerocorp.com',
            'phone_number' => '9800000000',
            'is_duplicate' => true,
        ]);
    }
}
