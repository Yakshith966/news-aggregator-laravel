<?php

namespace App\Http\Controllers;

use App\Actions\Article\ListArticlesAction;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/articles",
     *     summary="Get a list of articles",
     *     description="Retrieve a paginated list of articles with optional filters",
     *     tags={"Articles"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Search keyword for article titles",
     *         required=false,
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="from_date",
     *         in="query",
     *         description="Filter articles published from this date (YYYY-MM-DD)",
     *         required=false,
     *
     *         @OA\Schema(type="string", format="date")
     *     ),
     *
     *     @OA\Parameter(
     *         name="to_date",
     *         in="query",
     *         description="Filter articles published until this date (YYYY-MM-DD)",
     *         required=false,
     *
     *         @OA\Schema(type="string", format="date")
     *     ),
     *
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filter articles by category ID",
     *         required=false,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="author_id",
     *         in="query",
     *         description="Filter articles by author ID",
     *         required=false,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="source_id",
     *         in="query",
     *         description="Filter articles by source ID",
     *         required=false,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
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
     *
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 description="Pagination links",
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 description="Pagination metadata",
     *             )
     *         )
     *     )
     * )
     */
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
