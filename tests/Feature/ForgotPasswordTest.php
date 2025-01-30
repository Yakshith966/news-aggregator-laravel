<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_password_reset()
    {
        $email = 'test@example.com';

        // Simulate that the email exists in the system
        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => $email])
            ->andReturn(Password::RESET_LINK_SENT);

        $response = $this->postJson('/api/forgot-password', [
            'email' => $email,
        ]);

        $response->assertStatus(200)
        ->assertJson([
            // Change to match the actual message with exclamation mark
            'message' => 'We have emailed your password reset link!',
        ]);
    }

    public function test_password_reset_request_with_non_existing_email()
    {
        $email = 'blahblah@example.com';

        // Simulate that the email does not exist in the system
        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => $email])
            ->andReturn(Password::INVALID_USER);

        $response = $this->postJson('/api/forgot-password', [
            'email' => $email,
        ]);

        $response->assertStatus(400)
        ->assertJson([
            'message' => "We can't find a user with that email address.",
        ]);
    }

    public function test_password_reset_request_validation_error()
    {
        $response = $this->postJson('/api/forgot-password', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_password_reset_request_too_soon()
    {
        $email = 'test@example.com';

        // Simulate a throttle condition
        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => $email])
            ->andReturn('passwords.throttled');

        $response = $this->postJson('/api/forgot-password', [
            'email' => $email,
        ]);

        $response->assertStatus(400)
        ->assertJson([
            'message' => 'Please wait before retrying.',
        ]);
    }
}
