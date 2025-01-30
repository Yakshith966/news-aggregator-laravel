<?php

namespace App\Http\Controllers;

use App\Actions\Article\GetUserNewsfeedAction;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\Request;

class NewsFeedController extends Controller
{
    public function index(Request $request, GetUserNewsfeedAction $getUserNewsfeedAction)
    {
        $articles = $getUserNewsfeedAction->execute();

        return ArticleResource::collection($articles);
    }
}
