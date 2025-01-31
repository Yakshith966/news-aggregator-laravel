<?php

namespace App\Services;

use Illuminate\Http\Client\Factory;
use Illuminate\Support\Carbon;

class NewsAPIService implements NewsApiServiceInterface
{
    use NewsAPITrait;

    /**
     * The HTTP client instance.
     *
     * @var \Illuminate\Http\Client\Factory
     */
    private $http;

    /**
     * Constructor
     *
     * @param \Illuminate\Http\Client\Factory $http
     */
    public function __construct(Factory $http)
    {
        $this->http = $http;
    }

    public function fetchArticles(): array
    {
        $response = $this->http->get(config('services.newsapi.url'), [
            'apiKey' => config('services.newsapi.key'),
            'country' => 'us',
            'category' => 'technology',
        ]);

        $data = $response->json();

        if (isset($data['articles'])) {
            return $this->transformArticles($data['articles']);
        }

        return [];
    }

    private function transformArticles(array $articles): array
    {
        return array_map(function ($article) {
            return [
                'title' => $article['title'],
                'content' => $article['content'] ?? $article['description'] ?? 'Content not found',
                'published_at' => Carbon::parse($article['publishedAt']),
                'category_id' => $this->resolveCategoryId($article['category'] ?? 'general'),
                'author_id' => $this->resolveAuthorId($article['author'] ?? 'Unknown'),
                'source_id' => $this->resolveSourceId($article['source']['name'] ?? 'Unknown'),
            ];
        }, $articles);
    }

}
