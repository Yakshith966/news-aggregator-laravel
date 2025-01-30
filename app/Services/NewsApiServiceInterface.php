<?php

namespace App\Services;

interface NewsApiServiceInterface
{
    public function fetchArticles(): array;
}
