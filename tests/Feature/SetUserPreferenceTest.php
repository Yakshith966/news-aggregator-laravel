<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetUserPreferenceTest extends TestCase
{
    use RefreshDatabase;

    protected $user; // Remove the explicit 'User' type

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_can_store_a_user_preference_with_valid_data(): void
    {
        $data = [
            'preferencable_id' => 1,
            'preferencable_type' => 'category',
        ];

        $response = $this->actingAs($this->user)->postJson('/api/preferences', $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'user_id',
                'preferencable_id',
                'preferencable_type',
                'preferencable',
            ],
        ]);

        $this->assertDatabaseHas('preferences', [
            'user_id' => $this->user->id,
            'preferencable_id' => 1,
            'preferencable_type' => Category::class,
        ]);
    }

    public function test_returns_400_when_invalid_data_is_provided()
    {
        $response = $this->actingAs($this->user)->postJson('/api/preferences', [
            'preferencable_id' => 'invalid',
            'preferencable_type' => 'category',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['preferencable_id']);
    }

    public function test_returns_422_when_missing_required_fields()
    {
        $response = $this->actingAs($this->user)->postJson('/api/preferences', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['preferencable_id', 'preferencable_type']);
    }
}
