<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'user101@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user101@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'access_token',
                'token_type',
                'user' => ['id', 'email', 'name'],
            ]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_fail_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'user102@example.com',
            'password' => Hash::make('new-password'),
        ]);

        //Wrong password
        $response = $this->postJson('/api/login', [
            'email' => 'user102@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials.']);

        //Non-existing user
        $response2 = $this->postJson('/api/login', [
            'email' => 'user103@example.com',
            'password' => 'new-password',
        ]);

        $response2->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials.']);
    }

    public function test_fail_with_invalid_requests(): void
    {
        //Invalid email format
        $response = $this->postJson('/api/login', [
            'email' => 'invalid-email',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        //Missing email
        $response1 = $this->postJson('/api/login', [
            'password' => 'password',
        ]);

        $response1->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        //Missing password
        $response2 = $this->postJson('/api/login', [
            'email' => 'user104@example.com',
        ]);

        $response2->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
