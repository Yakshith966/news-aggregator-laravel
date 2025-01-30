<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Prepare user for testing
        $this->user = User::factory()->create([
            'name' => 'Amr Elsayed',
            'email' => 'test@unit.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        // Arrange: Try to log in with correct credentials
        $response = $this->postJson(route('login'), [
            'email' => $this->user->email,
            'password' => 'password',  // Correct password
        ]);

        // Assert: Successful login returns status 200 and structure
        $response->assertOk()
            ->assertJsonStructure([
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                ],
            ]);
    }

    public function test_user_cannot_login_with_wrong_credentials(): void
    {
        // Arrange: Try to log in with wrong password
        $response = $this->postJson(route('login'), [
            'email' => $this->user->email,
            'password' => 'wrongpassword',  // Incorrect password
        ]);

        // Assert: Login should fail with 401 Unauthorized
        $response->assertStatus(401);
    }

    public function test_login_fails_with_missing_email(): void
{
    $response = $this->postJson(route('login'), [
        'password' => 'password', // Remove this line to test missing email
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
}

public function test_login_fails_with_missing_password(): void
{
    $response = $this->postJson(route('login'), [
        'email' => $this->user->email,
        // Remove password to test missing field
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
}
}
