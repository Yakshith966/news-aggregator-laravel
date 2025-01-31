<?php

namespace App\Http\Controllers;

use App\Actions\Article\GetUserNewsfeedAction;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\Request;

class NewsFeedController extends Controller
{
     /**
     * @OA\Get(
     *     path="/api/newsfeed",
     *     summary="Get user newsfeed",
     *     description="Get customized user newsfeed based on his preferences",
     *     tags={"Articles"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page number",
     *         required=false,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User newsfeed retrieved",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/Article")
     *             ),
     *         )
     *     ),
     *
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index(Request $request, GetUserNewsfeedAction $getUserNewsfeedAction)
    {
        $articles = $getUserNewsfeedAction->execute();

        return ArticleResource::collection($articles);
    }
}
