<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_has_expected_fields(): void
    {
        $category = Category::factory()->create();

        $this->assertEquals([
            'name',
            'updated_at',
            'created_at',
            'id',
        ], array_keys($category->toArray()));
    }
}
