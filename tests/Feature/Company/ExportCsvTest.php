<?php

namespace Tests\Feature\Company;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportCsvTest extends TestCase
{
    use RefreshDatabase;

    public function test_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Company::factory()->create([
            'company_name' => 'Zero Corp',
            'email'        => 'info@zerocorp.com',
            'phone_number' => '9800000000',
            'is_duplicate'=> false,
        ]);

        Company::factory()->create([
            'company_name' => 'Nepal Tech',
            'email'        => 'contact@nepaltech.com',
            'phone_number' => '9800000001',
            'is_duplicate'=> true,
        ]);

        $response = $this->get('/api/companies/export');

        $response->assertStatus(200);

        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $csvContent = str_replace('"', '', $response->streamedContent());
        $this->assertStringContainsString('Company Name,Email,Phone Number,Is Duplicate', $csvContent);
        $this->assertStringContainsString('Zero Corp,info@zerocorp.com,9800000000,No', $csvContent);
        $this->assertStringContainsString('Nepal Tech,contact@nepaltech.com,9800000001,Yes', $csvContent);
    }
}
