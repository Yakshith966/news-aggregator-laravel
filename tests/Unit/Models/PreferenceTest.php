<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Preference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreferenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_has_expected_fields(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $preference = Preference::create([
            'user_id' => $user->id,
            'preferencable_id' => $category->id,
            'preferencable_type' => Category::class,
        ]);

        $this->assertEqualsCanonicalizing([
            'id',
            'user_id',
            'preferencable_id',
            'preferencable_type',
            'created_at',
            'updated_at',
        ], array_keys($preference->toArray()));
    }
}
