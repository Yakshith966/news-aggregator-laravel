<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Preference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewfeedTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_it_returns_empty_result_if_no_preferences(): void
    {
        $response = $this->actingAs($this->user)->getJson('/api/newsfeed');

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [],
        ]);

        $this->assertEmpty($response->json('data'));
    }

    public function test_it_returns_articles_matching_user_preferences(): void
    {
        $category = Category::factory()->create();
        $article = Article::factory()->create(['category_id' => $category->id]);

        Preference::create([
            'user_id' => $this->user->id,
            'preferencable_id' => $category->id,
            'preferencable_type' => Category::class,
        ]);

        $response = $this->actingAs($this->user)->getJson('/api/newsfeed');

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $article->id,
            'title' => $article->title,
        ]);
    }
}
