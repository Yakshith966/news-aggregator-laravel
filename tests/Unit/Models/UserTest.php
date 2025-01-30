<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Preference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_has_expected_fields(): void
    {
        $user = User::factory()->create();

        $this->assertEquals([
            'name',
            'email',
            'email_verified_at',
            'updated_at',
            'created_at',
            'id',
        ], array_keys($user->toArray()));
    }

    public function test_model_has_preferences_relation()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $user->preferences()->create([
            'preferencable_id' => $category->id,
            'preferencable_type' => Category::class,
        ]);

        $this->assertInstanceOf(Preference::class, $user->preferences->first());
    }
}
