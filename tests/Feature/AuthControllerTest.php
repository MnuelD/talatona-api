<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        Role::create(['name' => 'user']);

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'telefone' => '923456789',
            'role' => 'user'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => ['id', 'name', 'email', 'telefone'],
                'role'
            ]);
    }

    public function test_user_can_login_with_email()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
            'method' => 'email'
        ]);

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_get_profile()
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id', 'name', 'email'
            ]);
    }

    public function test_user_can_logout()
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200);
    }
}
