<?php

namespace App\Actions\Article;

use App\Models\Article;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ListArticlesAction
{
    /**
     * Get list of articles with filters
     *
     * @param  array  $filters  ['keyword', 'from_date', 'to_date', 'category_id', 'author_id', 'source_id']
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function execute(array $filters): LengthAwarePaginator
    {
        $cacheKey = $this->getCacheKey($filters);
        Cache::forget($cacheKey);
        $products = Cache::remember($cacheKey, 60 * 60, function () use ($filters) {
            $query = Article::query()
                ->when(
                    isset($filters['keyword']),
                    function ($query) use ($filters) {
                        $query->where('title', 'like', '%' . $filters['keyword'] . '%');
                    }
                )
                ->when(
                    isset($filters['from_date']),
                    function ($query) use ($filters) {
                        $query->whereDate('published_at', '>=', $filters['from_date']);
                    }
                )
                ->when(
                    isset($filters['to_date']),
                    function ($query) use ($filters) {
                        $query->whereDate('published_at', '<=', $filters['to_date']);
                    }
                )
                ->when(
                    isset($filters['category_id']),
                    function ($query) use ($filters) {
                        $query->where('category_id', $filters['category_id']);
                    }
                )
                ->when(
                    isset($filters['author_id']),
                    function ($query) use ($filters) {
                        $query->where('author_id', $filters['author_id']);
                    }
                )
                ->when(
                    isset($filters['source_id']),
                    function ($query) use ($filters) {
                        $query->where('source_id', $filters['source_id']);
                    }
                );

            $query->with(['author', 'category', 'source']);

            return $query->paginate(10);
        });

        return $products;
    }
    


    private function getCacheKey(array $filters): string
    {
        $key = 'articles:';

        ksort($filters);

        $query_str = http_build_query($filters);

        return $key.$query_str;
    }
}
