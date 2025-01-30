<?php

namespace App\Http\Controllers;

use App\Actions\Article\ListArticlesAction;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request, ListArticlesAction $listArticlesAction)
    {
        $articles = $listArticlesAction->execute($request->all());
        return ArticleResource::collection($articles);
    }

    public function show(Request $request, Article $article): ArticleResource
    {
        $article->load(['category', 'author', 'source']);
        return new ArticleResource($article);
    }
}
