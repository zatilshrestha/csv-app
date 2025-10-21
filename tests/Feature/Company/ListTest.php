<?php

namespace Tests\Feature\Company;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListTest extends TestCase
{
    use RefreshDatabase;

    public function test_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Company::factory()->count(20)->create();

        $response = $this->getJson('/api/companies');

        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertCount(10, $responseData['data']);
        $this->assertEquals(1, $responseData['meta']['current_page']);
        $this->assertEquals(2, $responseData['meta']['last_page']);
        $this->assertEquals(20, $responseData['meta']['total']);
    }

    public function test_fail_unauthorize(): void
    {
        $response = $this->getJson('/api/companies');

        $response->assertStatus(401);
    }
}
