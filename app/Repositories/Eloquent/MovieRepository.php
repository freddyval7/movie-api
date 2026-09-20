<?php

namespace App\Repositories\Eloquent;

use App\Models\Movie;
use App\Repositories\Contracts\MovieRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class MovieRepository extends BaseRepository implements MovieRepositoryInterface
{
    private const SORTABLE = ['title', 'rating', 'created_at'];

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
                return $query->whereRaw('LOWER(title) LIKE ?',
                    ['%'.strtolower($search).'%']);
            });
        $query->when($filters['year'] ?? null,
            function ($query, $year) {
                return $query->where('year', $year);
            });
        $query->when($filters['min_rating'] ?? null,
            function ($query, $minRating) {
                return $query->where('rating', '>=', $minRating);
            });
        $query->when($filters['genre_id'] ?? null,
            function ($query, $genreId) {
                return $query->whereHas('genres', function ($subQuery) use ($genreId) {
                    $subQuery->where('genre.id', $genreId);
                });
            });

        $sortColumn = in_array($sortBy, self::SORTABLE) ? $sortBy : 'title';

        $order = $order === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sortColumn, $order)->get();
    }

    public function syncGenres(Movie $movie, array $genresIds): void
    {
        $movie->genres()->sync($genresIds);
    }
}
