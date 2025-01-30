<?php

namespace App\Actions\Article;

use App\Actions\Preference\GetUserPreferencesAction;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class GetUserNewsfeedAction
{
    /**
     * @var \App\Actions\Preference\GetUserPreferencesAction
     */
    private $getUserPreferencesAction;

    /**
     * Constructor to initialize dependencies.
     *
     * @param \App\Actions\Preference\GetUserPreferencesAction $getUserPreferencesAction
     */
    public function __construct(GetUserPreferencesAction $getUserPreferencesAction)
    {
        $this->getUserPreferencesAction = $getUserPreferencesAction;
    }

    /**
     * Get the user's customized newsfeed.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|array
     */
    public function execute(): LengthAwarePaginator
    {
        $preferences = $this->getUserPreferencesAction->execute();

        if ($preferences->isEmpty()) {
            return new LengthAwarePaginator(
                [], 
                0,  
                10, 
                1, 
                ['path' => url()->current()] 
            );
        }

        $typeIds = $this->getTypesIds($preferences);

        $articlesQuery = Article::query();

        foreach ($typeIds as $column => $ids) {
            if (!empty($ids)) {
                $articlesQuery->orWhereIn($column, $ids);
            }
        }

        return $articlesQuery->with(['category', 'author', 'source'])
            ->orderBy('published_at', 'desc')
            ->paginate(10);
    }

    /**
     * Map preferences to foreign key IDs.
     *
     * @param \Illuminate\Database\Eloquent\Collection $preferences
     * @return array
     */
    private function getTypesIds(Collection $preferences): array
    {
        $typeIds = [];

        foreach ($preferences as $preference) {
            $column = $this->getForeignKey($preference->preferencable_type);

            if ($column) {
                $typeIds[$column][] = $preference->preferencable_id;
            }
        }

        return $typeIds;
    }

    /**
     * Get the foreign key column for a given model type.
     *
     * @param string $type
     * @return string|null
     */
    private function getForeignKey(string $type): ?string
    {
        $mapping = [
            Category::class => 'category_id',
            Author::class => 'author_id',
            Source::class => 'source_id',
        ];

        return $mapping[$type] ?? null;
    }
}
