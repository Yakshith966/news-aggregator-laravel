<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @OA\Schema(
 *     schema="Article",
 *     type="object",
 *     title="Article Resource",
 *     description="Article resource representation",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="eius ea eveniet voluptatibus accusantium"),
 *     @OA\Property(property="content", type="string", example="eius ea eveniet voluptatibus accusantium"),
 *     @OA\Property(property="published_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
 *     @OA\Property(property="category", type="object", ref="#/components/schemas/Category"),
 *     @OA\Property(property="author", type="object", ref="#/components/schemas/Author"),
 *     @OA\Property(property="source", type="object", ref="#/components/schemas/Source"),
 * )
 */
class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'published_at' => $this->published_at,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'author' => new AuthorResource($this->whenLoaded('author')),
            'source' => new SourceResource($this->whenLoaded('source')),
        ];
    }
}
