<?php

namespace App\Services;

use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Carbon;

class TheGuardianService implements NewsApiServiceInterface
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
     * Fetch articles from The Guardian API.
     *
     * @return array
     */
    public function fetchArticles(): array
    {
        $response = $this->http->get(config('services.guardian.url'), [
            'api-key' => config('services.guardian.key'),
        ]);

        $data = $response->json();

        if (isset($data['response']['results'])) {
            return $this->transformArticles($data['response']['results']);
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
                'title' => $article['webTitle'],
                'content' => $article['webTitle'],
                'published_at' => Carbon::parse($article['webPublicationDate']),
                'category_id' => $this->resolveCategoryId($article['sectionName'] ?? 'general'),
                'author_id' => $this->resolveAuthorId('The Guardian'),
                'source_id' => $this->resolveSourceId('The Guardian'),
            ];
        }, $articles);
    }
}
