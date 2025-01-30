<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Services\NewsApiServiceInterface;
use Illuminate\Console\Command;

class FetchNewsArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch {service=newsapi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch news articles from selected external news service';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $service = $this->argument('service');

        // dd($service);

        $newsApiService = app(NewsApiServiceInterface::class, ['service' => $service]);

        // dd($newsApiService);

        $this->fetchArticlesFromService($newsApiService);

        $this->info('Articles fetched and updated successfully.');
    }

    /**
     * Fetch articles from a specific service.
     */
    private function fetchArticlesFromService($service)
    {
        $articles = $service->fetchArticles();

        // dd($articles);

        foreach ($articles as $articleData) {
            Article::updateOrCreate(
                ['source_id' => $articleData['source_id'], 'title' => $articleData['title']],
                $articleData
            );
        }
    }
}
