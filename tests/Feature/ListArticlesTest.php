<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    use RefreshDatabase;

    protected  $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Category::factory()->count(3)->create();
        Author::factory()->count(3)->create();
        Source::factory()->count(3)->create();
        Article::factory()->count(20)->create();
    }

    public function test_it_returns_paginated_articles()
    {
        $response = $this->actingAs($this->user)->getJson('/api/articles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'content',
                        'published_at',
                        'category' => ['id', 'name'],
                        'author' => ['id', 'name'],
                        'source' => ['id', 'name'],
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_it_filters_articles_by_keyword()
    {
        $article = Article::factory()->create(['title' => 'Unique Keyword']);

        $response = $this->actingAs($this->user)->getJson('/api/articles?keyword=Keyword');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => $article->title]);
    }

    public function test_it_filters_articles_by_date_range()
    {
        $article = Article::factory()->create(['published_at' => '2023-01-01']);

        $response = $this->actingAs($this->user)->getJson('/api/articles?from_date=2023-01-01&to_date=2023-01-01');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => $article->id]);
    }

    public function test_it_filters_articles_by_category()
    {
        $category = Category::factory()->create();
        Article::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->user)->getJson('/api/articles?category_id='.$category->id);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['category' => ['id' => $category->id, 'name' => $category->name]]);
    }

    public function test_it_filters_articles_by_author()
    {
        $author = Author::factory()->create();
        Article::factory()->create(['author_id' => $author->id]);

        $response = $this->actingAs($this->user)->getJson('/api/articles?author_id='.$author->id);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['author' => ['id' => $author->id, 'name' => $author->name]]);
    }

    public function test_it_filters_articles_by_source()
    {
        $source = Source::factory()->create();
        Article::factory()->create(['source_id' => $source->id]);

        $response = $this->actingAs($this->user)->getJson('/api/articles?source_id='.$source->id);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['source' => ['id' => $source->id, 'name' => $source->name]]);
    }
}
