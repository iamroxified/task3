<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Support\Str;
use App\Http\Controllers\Auth;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testUserRegistration()
    {
        $response = $this->postJson('/api/auth/register', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'phone' => '1234567890',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'accessToken',
                        'user' => [
                            'userId',
                            'firstName',
                            'lastName',
                            'email',
                            'phone',
                        ],
                    ],
                 ]);

        $this->assertDatabaseHas('users', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertDatabaseHas('organisations', [
            'name' => "John's Organisation",
        ]);
    }

    public function testUserLogin()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'accessToken',
                        'user' => [
                            'userId',
                            'firstName',
                            'lastName',
                            'email',
                            'phone',
                        ],
                    ],
                 ]);
    }

    public function testGetUserRecord()
    {
        $user = User::factory()->create();

        $token = auth()->login($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->getJson("/api/users/{$user->userId}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'userId',
                        'firstName',
                        'lastName',
                        'email',
                        'phone',
                    ],
                 ]);
    }

    public function testGetAllOrganisations()
    {
        $user = User::factory()->create();
        $organisation = Organisation::factory()->create();
        $user->organisations()->attach($organisation->orgId);

        $token = auth()->login($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->getJson('/api/organisations');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'organisations' => [
                            '*' => [
                                'orgId',
                                'name',
                                'description',
                            ],
                        ],
                    ],
                 ]);
    }

    public function testCreateOrganisation()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/organisations', [
                            'name' => 'New Organisation',
                            'description' => 'A test organisation',
                         ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'orgId',
                        'name',
                        'description',
                    ],
                 ]);
    }

    public function testAddUserToOrganisation()
    {
        $user = User::factory()->create();
        $organisation = Organisation::factory()->create();
        $token = auth()->login($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson("/api/organisations/{$organisation->orgId}/users", [
                            'userId' => $user->userId,
                         ]);

        $response->assertStatus(200)
                 ->assertJson([
                    'status' => 'success',
                    'message' => 'User added to organisation successfully',
                 ]);

        $this->assertTrue($organisation->users->contains($user));
    }
}
