<?php

namespace Tests\Unit\Services;

use App\Services\NewsAPIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Factory as HttpClient;
use Mockery;
use Tests\TestCase;

class NewsAPIServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetches_and_transform_articles_correctly()
    {
        // Mock API response
        $mockApiResponse = [
            'status' => 'ok',
            'totalResults' => 1,
            'articles' => [
                [
                    'title' => 'Breaking News',
                    'content' => 'This is the news content.',
                    'source' => ['name' => 'BBC News'],
                    'author' => 'John Doe',
                    'publishedAt' => now()->toISOString(),
                ],
            ],
        ];

        $mockHttpClient = Mockery::mock(HttpClient::class);
        $mockHttpClient->shouldReceive('get')
            ->once()
            ->with(
                config('services.newsapi.url'),
                [
                    'apiKey' => config('services.newsapi.key'),
                    'country' => 'us',
                    'category' => 'technology',
                ]
            )
            ->andReturn(new \Illuminate\Http\Client\Response(
                new \GuzzleHttp\Psr7\Response(
                    200,
                    [],
                    json_encode($mockApiResponse)
                )
            ));

        $this->app->instance(HttpClient::class, $mockHttpClient);

        $newsService = new NewsAPIService($mockHttpClient);
        $articles = $newsService->fetchArticles();

        $this->assertEquals('Breaking News', $articles[0]['title']);
        $this->assertEquals('This is the news content.', $articles[0]['content']);
        // $this->assertEquals(1, $articles[0]['source_id']);
    }
}
