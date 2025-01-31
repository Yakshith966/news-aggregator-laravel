<?php

namespace App\Http\Resources;

use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @OA\Schema(
 *     schema="Preference",
 *     type="object",
 *     title="Preference Resource",
 *     description="Preference resource representation",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="preferencable_id", type="integer", example=1),
 *     @OA\Property(property="preferencable_type", type="string", example="App\\Models\\Category"),
 * @OA\Property(
 *         property="preferencable",
 *         type="object",
 *         oneOf={
 *
 *             @OA\Schema(ref="#/components/schemas/Category"),
 *             @OA\Schema(ref="#/components/schemas/Author"),
 *             @OA\Schema(ref="#/components/schemas/Source")
 *         },
 *         description="Preferencable object"
 *     )
 * )
 */
class PreferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $resourceClass = $this->getResourceClass();

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'preferencable_id' => $this->preferencable_id,
            'preferencable_type' => $this->preferencable_type,
            'preferencable' => new $resourceClass($this->preferencable),
        ];
    }

    private function getResourceClass(): ?string
    {
        $typeMap = [
            Category::class => 'App\Http\Resources\CategoryResource',
            Author::class => 'App\Http\Resources\AuthorResource',
            Source::class => 'App\Http\Resources\SourceResource',
        ];

        return $typeMap[$this->preferencable_type] ?? null;
    }
}
