<?php

namespace App\Services;

use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Carbon;

class NYTimesService implements NewsApiServiceInterface
{
    use NewsAPITrait;

    /**
     * The HTTP client instance.
     *
     * @var \Illuminate\Http\Client\Factory
     */
    private $http;

    /**
     * Constructor to inject the HTTP client.
     *
     * @param \Illuminate\Http\Client\Factory $http
     */
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /**
     * Fetch articles from NYTimes API.
     *
     * @return array
     */
    public function fetchArticles(): array
    {
        $response = $this->http->get(config('services.nytimes.url'), [
            'api-key' => config('services.nytimes.key'),
        ]);

        $data = $response->json();

        if (isset($data['results'])) {
            return $this->transformArticles($data['results']);
        }

        return [];
    }

    /**
     * Transform API articles into a standard format.
     *
     * @param array $articles
     * @return array
     */
    private function transformArticles(array $articles): array
    {
        return array_map(function ($article) {
            return [
                'title' => $article['title'],
                'content' => $article['abstract'],
                'published_at' => Carbon::parse($article['published_date']),
                'category_id' => $this->resolveCategoryId($article['section'] ?? 'general'),
                'author_id' => $this->resolveAuthorId($article['byline'] ?? 'unknown'),
                'source_id' => $this->resolveSourceId('NYTimes'),
            ];
        }, $articles);
    }
}
