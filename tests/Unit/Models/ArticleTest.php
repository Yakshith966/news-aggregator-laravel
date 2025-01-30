<?php

namespace Tests\Unit\Models;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_has_expected_fields(): void
    {
        $article = Article::factory()->create();

        $this->assertEquals([
            'title',
            'content',
            'category_id',
            'author_id',
            'source_id',
            'published_at',
            'updated_at',
            'created_at',
            'id',
        ], array_keys($article->toArray()));
    }

    public function test_model_has_category_relation()
    {
        $artcile = Article::factory()->create();

        $this->assertInstanceOf(Category::class, $artcile->category);
    }

    public function test_model_has_author_relation()
    {
        $artcile = Article::factory()->create();

        $this->assertInstanceOf(Author::class, $artcile->author);
    }

    public function test_model_has_source_relation()
    {
        $artcile = Article::factory()->create();

        $this->assertInstanceOf(Source::class, $artcile->source);
    }
}
