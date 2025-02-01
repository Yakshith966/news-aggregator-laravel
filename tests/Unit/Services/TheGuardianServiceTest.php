<?php

namespace Tests\Unit\Services;

use App\Http\Middleware\TrustProxies;
use App\Services\TheGuardianService;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Http\Client\Response;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Mockery;
use Tests\TestCase;

class TheGuardianServiceTest extends TestCase
{
    public function test_fetches_and_transforms_articles_from_the_guardian()
    {
        // Correct API response structure
        $mockApiResponse = [
            'response' => [
                'status' => 'ok',
                'results' => [
                    [
                        'sectionName' => 'World news',
                        'webPublicationDate' => '2022-10-21T14:06:14Z',
                        'webTitle' => 'Russia-Ukraine war latest: what we know on day 240 of the invasion',
                        'pillarName' => 'News',
                        'webUrl' => 'https://example.com/article',
                        'fields' => [
                            'thumbnail' => 'https://example.com/image.jpg'
                        ]
                    ],
                ]
            ]
        ];

        // Create a mock instance that extends HttpClient
        $mockHttpClient = Mockery::mock(HttpClient::class);
        
        // Set up the expected call with exact parameters
        $mockHttpClient->shouldReceive('get')
            ->once()
            ->with(
                config('services.guardian.url'),
                [
                    'api-key' => config('services.guardian.key'),
                ]
            )
            ->andReturn(new Response(
                new GuzzleResponse(200, [], json_encode($mockApiResponse))
            ));
            // dd($mockHttpClient);
        // Bind the mock to the service container
        $this->app->instance(HttpClient::class, $mockHttpClient);
        
        // Resolve the service from the container
        $newsService = $this->app->make(TheGuardianService::class);
        $articles = $newsService->fetchArticles();
        

        // Assertions
        $this->assertCount(1, $articles);
        $this->assertEquals('Russia-Ukraine war latest: what we know on day 240 of the invasion', $articles[0]['title']);
        $this->assertEquals('Russia-Ukraine war latest: what we know on day 240 of the invasion', $articles[0]['content']);
        // $this->assertEquals(156, $articles[0]['category_id']);
    }
}