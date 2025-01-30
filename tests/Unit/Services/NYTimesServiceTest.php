<?php

namespace Tests\Unit\Services;

use App\Services\NYTimesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Factory as HttpClient;
use Mockery;
use Tests\TestCase;

class NYTimesServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetches_and_transforms_articles_from_nytimes()
    {
        $mockApiResponse = [
            'status' => 'OK',
            'results' => [
                [
                    'title' => 'Breaking News from NYTimes',
                    'abstract' => 'This is the news content from NYTimes.',
                    'published_date' => now(),
                    'section' => 'Technology',
                    'byline' => 'John Doe',
                ],
            ],
        ];

        $mockHttpClient = Mockery::mock(HttpClient::class);
        $mockHttpClient->shouldReceive('get')
            ->once()
            ->with(config('services.nytimes.url'), [
                'api-key' => config('services.nytimes.key'),
            ])
            ->andReturn(new \Illuminate\Http\Client\Response(
                new \GuzzleHttp\Psr7\Response(200, [], json_encode($mockApiResponse))
            ));

        $this->app->instance(HttpClient::class, $mockHttpClient);

        $newsService = new NYTimesService($mockHttpClient);
        $articles = $newsService->fetchArticles();

        $this->assertCount(1, $articles);
        $this->assertEquals('Breaking News from NYTimes', $articles[0]['title']);
        $this->assertEquals('This is the news content from NYTimes.', $articles[0]['content']);
    }
}
