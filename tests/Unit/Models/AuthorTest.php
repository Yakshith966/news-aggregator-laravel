<?php

namespace Tests\Unit\Models;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_has_expected_fields(): void
    {
        $author = Author::factory()->create();

        $this->assertEquals([
            'name',
            'updated_at',
            'created_at',
            'id',
        ], array_keys($author->toArray()));
    }
}
