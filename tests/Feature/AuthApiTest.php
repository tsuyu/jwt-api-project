<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    private function authHeaders(string $token): array
    {
        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
    }

    public function test_register_validation_errors(): void
    {
        $res = $this->postJson('/api/auth/register', []);
        $res->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonStructure(['errors' => ['name', 'username', 'email', 'password']]);
    }

    public function test_register_success(): void
    {
        $payload = [
            'name' => 'New User',
            'username' => 'newuser',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $res = $this->postJson('/api/auth/register', $payload);
        $res->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'username', 'email']]);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
        ]);
    }

    public function test_login_validation_errors(): void
    {
        $res = $this->postJson('/api/auth/login', []);
        $res->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonStructure(['errors' => ['username', 'password']]);
    }

    public function test_login_invalid_credentials(): void
    {
        User::factory()->create([
            'username' => 'testuser1',
            'email' => 'test@example.com',
            'password' => Hash::make('correct_password'),
        ]);

        $res = $this->postJson('/api/auth/login', [
            'username' => 'testuser1',
            'password' => 'wrong_password',
        ]);

        $res->assertStatus(401)
            ->assertJsonPath('success', false);
    }

    public function test_login_success_and_me_endpoint(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser2',
            'email' => 'test2@example.com',
            'password' => Hash::make('password123'),
        ]);

        $login = $this->postJson('/api/auth/login', [
            'username' => 'testuser2',
            'password' => 'password123',
        ]);

        $login->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'username', 'email'], 'expires_in']);

        $token = $login->json('token');

        // Access protected me endpoint
        $me = $this->getJson('/api/auth/me', $this->authHeaders($token));
        $me->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('user.username', 'testuser2');
    }

    public function test_me_requires_authentication(): void
    {
        $this->getJson('/api/auth/me')->assertStatus(401);
    }

    public function test_protected_routes_require_and_accept_token(): void
    {
        // Without token should be 401
        $this->getJson('/api/protected')->assertStatus(401);

        $user = User::factory()->create([
            'username' => 'testuser3',
            'email' => 'test3@example.com',
            'password' => Hash::make('password123'),
        ]);

        $login = $this->postJson('/api/auth/login', [
            'username' => 'testuser3',
            'password' => 'password123',
        ]);

        $token = $login->json('token');

        $this->getJson('/api/protected', $this->authHeaders($token))
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['message', 'user', 'timestamp']);

        $this->getJson('/api/user', $this->authHeaders($token))
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('user.username', 'testuser3');
    }

    public function test_logout_invalidates_token(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser4',
            'email' => 'test4@example.com',
            'password' => Hash::make('password123'),
        ]);

        $login = $this->postJson('/api/auth/login', [
            'username' => 'testuser4',
            'password' => 'password123',
        ]);

        $token = $login->json('token');

        $logout = $this->postJson('/api/auth/logout', [], $this->authHeaders($token));
        $logout->assertOk()
            ->assertJsonPath('success', true);

        // Token should be blacklisted/invalid now
        $this->getJson('/api/auth/me', $this->authHeaders($token))
            ->assertStatus(401);
    }

    public function test_refresh_returns_new_token_and_can_access(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser5',
            'email' => 'test5@example.com',
            'password' => Hash::make('password123'),
        ]);

        $login = $this->postJson('/api/auth/login', [
            'username' => 'testuser5',
            'password' => 'password123',
        ]);
        $oldToken = $login->json('token');

        $refresh = $this->postJson('/api/auth/refresh', [], $this->authHeaders($oldToken));
        $refresh->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['token', 'expires_in']);

        $newToken = $refresh->json('token');

        // Use new token to access me
        $this->getJson('/api/auth/me', $this->authHeaders($newToken))
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['user' => ['id', 'email', 'name']]);
    }
}
