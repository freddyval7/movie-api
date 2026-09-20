<?php

namespace App\Repositories\Eloquent;

use App\Models\Movie;
use App\Repositories\Contracts\MovieRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class MovieRepository extends BaseRepository implements MovieRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(Movie $model)
    {
        parent::__construct($model);
    }

    public function filter(
        array $filters,
        string $sortBy = 'title',
        string $order = 'asc'
    ): Collection {
        $query = Movie::with('genres');
        $query->when($filters['search'] ?? null,
            function ($query, $search) {
                $query->whereRaw('LOWER(title) LIKE ?',
                    ['%'.strtolower($search).'%']);
            });
        $query->when($filters['year'] ?? null,
            function ($query, $year) {
                $query->where('year', $year);
            });
        $query->when($filters['min_rating'] ?? null,
            function ($query, $minRating) {
                $query->where('rating', '>=', $minRating);
            });

        return $query->get();
    }

    public function syncGenres(): void
    {
        //
    }

    public function restore(): void
    {
        // TODO: Implement restore() method.
    }
}
