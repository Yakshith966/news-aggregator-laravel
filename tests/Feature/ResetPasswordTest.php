<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Mockery;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_password_reset(): void
    {
        $data = [
            'token' => '123456abcdef',
            'email' => 'test@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ];

        // Mock the Password facade
        Password::shouldReceive('reset')
            ->once()
            ->withArgs(function ($credentials, $callback) use ($data) {
                $this->assertEquals($data['token'], $credentials['token']);
                $this->assertEquals($data['email'], $credentials['email']);
                $this->assertEquals($data['password'], $credentials['password']);

                $mockUser = Mockery::mock(User::class);

                $mockUser->shouldReceive('forceFill')
                    ->once()
                    ->with(Mockery::on(function ($args) use ($data) {
                        return Hash::check($data['password'], $args['password']);
                    }))
                    ->andReturnSelf();

                $mockUser->shouldReceive('save')->once();

                $callback($mockUser, $credentials['password']);

                return true;
            })
            ->andReturn(Password::PASSWORD_RESET);

        $response = $this->postJson('/api/reset-password', $data);

        $response->assertStatus(200)
        ->assertJson([
            'message' => 'Your password has been reset!', // Add exclamation mark
        ]);
    }

    public function test_failed_password_reset_with_invalid_token(): void
    {
        $data = [
            'token' => 'invalidtoken',
            'email' => 'test@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ];

        Password::shouldReceive('reset')
            ->once()
            ->withArgs(function ($credentials, $callback) use ($data) {
                $this->assertEquals($data['token'], $credentials['token']);
                $this->assertEquals($data['email'], $credentials['email']);
                $this->assertEquals($data['password'], $credentials['password']);

                return true;
            })
            ->andReturn(Password::INVALID_TOKEN);

        $response = $this->postJson('/api/reset-password', $data);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'This password reset token is invalid.',
            ]);
    }

    public function test_password_reset_validation_error()
    {
        $response = $this->postJson('/api/reset-password', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['token', 'email', 'password']);
    }

    public function test_password_reset_with_mismatched_passwords()
    {
        $data = [
            'token' => '123456abcdef',
            'email' => 'test@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'differentpassword',
        ];

        $response = $this->postJson('/api/reset-password', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_password_reset_with_weak_password()
    {
        $data = [
            'token' => '123456abcdef',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ];

        $response = $this->postJson('/api/reset-password', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
