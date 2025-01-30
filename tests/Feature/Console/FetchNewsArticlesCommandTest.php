<?php

namespace Tests\Feature\Console;

use App\Services\NewsApiServiceInterface;
use Mockery;
use Tests\TestCase;

class FetchNewsArticlesCommandTest extends TestCase
{
    public function test_executes_news_fetch_command_for_newsapi()
    {
        $mock = Mockery::mock(NewsApiServiceInterface::class);
        $mock->shouldReceive('fetchArticles')->once()->andReturn([]);

        $this->app->bind(NewsApiServiceInterface::class, function () use ($mock) {
            return $mock;
        });

        $this->artisan('news:fetch newsapi')
            ->assertExitCode(0)
            ->expectsOutput('Articles fetched and updated successfully.');
    }
}
