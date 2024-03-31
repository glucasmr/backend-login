<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_register_with_valid_data()
    {
        $user = [
            'name' => 'User Test',
            'username' => 'user.test',
            'email' => 'user@test.com',
            'password' => 'P@ssw0rd',
            'password_confirmation' => 'P@ssw0rd',
        ];

        $response = $this->postJson('/api/register', $user);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) use ($user) {

            $json->hasAll(['message', 'user', 'token']);

            $json->where('user.name', $user['name'])
                ->where('user.username', $user['username'])
                ->where('user.email', $user['email']);
        });
    }

    public function test_register_with_invalid_data()
    {
        $userData = [
            'name' => '',
            'username' => '',
            'email' => 'notanemail',
            'password' => 'short',
            'password_confirmation' => 'short',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'username', 'email', 'password']);
    }

    public function test_login_with_valid_credentials()
    {
        User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('testpassword'),
        ]);

        $response = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'testpassword',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['user', 'message', 'token']);
    }

    public function test_login_with_invalid_credentials()
    {
        User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('testpassword'),
        ]);

        $response = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'wr0ngP@ssword',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Invalid login details']);
    }
}
