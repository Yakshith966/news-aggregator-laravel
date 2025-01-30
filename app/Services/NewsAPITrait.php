<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Category;
use App\Models\Source;

trait NewsAPITrait
{
    private function resolveCategoryId(string $categoryName): int
    {
        return Category::firstOrCreate(['name' => $categoryName])->id;
    }

    private function resolveAuthorId(?string $authorName): int
    {
        return Author::firstOrCreate(['name' => $authorName ?? 'Unknown'])->id;
    }

    private function resolveSourceId(string $sourceName): int
    {
        return Source::firstOrCreate(['name' => $sourceName])->id;
    }
}
