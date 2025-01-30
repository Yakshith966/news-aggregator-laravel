<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowArticleTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $category = Category::factory()->create();
        $author = Author::factory()->create();
        $source = Source::factory()->create();

        Article::factory()->create([
            'title' => 'eius ea eveniet voluptatibus accusantium',
            'content' => 'Quis illo necessitatibus officia amet consectetur. Recusandae quibusdam nam sit quia magni. Repudiandae laborum autem corporis est velit velit.\n\nAsperiores expedita ut et distinctio accusamus doloremque dolores. Sit rerum dolores ea natus. Officia et ut voluptatibus et. Rerum beatae velit vel at qui architecto nihil.\n\nDelectus reiciendis incidunt eligendi. Est eum rerum sequi et et magni. Nostrum quisquam optio nam et ut.',
            'published_at' => '2024-02-05 23:46:14',
            'category_id' => $category->id,
            'author_id' => $author->id,
            'source_id' => $source->id,
        ]);
    }

    public function test_show_article_success()
    {
        $article = Article::first();

        $response = $this->actingAs($this->user)->json('GET', '/api/articles/'.$article->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'content',
                'published_at',
                'category' => [
                    'id',
                    'name',
                ],
                'author' => [
                    'id',
                    'name',
                ],
                'source' => [
                    'id',
                    'name',
                ],
            ],
        ]);

        $response->assertJson([
            'data' => [
                'id' => $article->id,
                'title' => 'eius ea eveniet voluptatibus accusantium',
                'content' => 'Quis illo necessitatibus officia amet consectetur. Recusandae quibusdam nam sit quia magni. Repudiandae laborum autem corporis est velit velit.\n\nAsperiores expedita ut et distinctio accusamus doloremque dolores. Sit rerum dolores ea natus. Officia et ut voluptatibus et. Rerum beatae velit vel at qui architecto nihil.\n\nDelectus reiciendis incidunt eligendi. Est eum rerum sequi et et magni. Nostrum quisquam optio nam et ut.',
                'published_at' => '2024-02-05 23:46:14',
                'category' => [
                    'id' => $article->category->id,
                    'name' => $article->category->name,
                ],
                'author' => [
                    'id' => $article->author->id,
                    'name' => $article->author->name,
                ],
                'source' => [
                    'id' => $article->source->id,
                    'name' => $article->source->name,
                ],
            ],
        ]);
    }

    public function test_show_article_not_found()
    {
        $response = $this->actingAs($this->user)->json('GET', '/api/articles/99999');

        $response->assertStatus(404);
    }
}
