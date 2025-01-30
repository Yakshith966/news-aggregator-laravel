<?php

namespace Tests\Unit\Models;

use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_has_expected_fields(): void
    {
        $source = Source::factory()->create();

        $this->assertEquals([
            'name',
            'updated_at',
            'created_at',
            'id',
        ], array_keys($source->toArray()));
    }
}
