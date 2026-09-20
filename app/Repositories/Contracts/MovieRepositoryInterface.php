<?php

namespace App\Repositories\Contracts;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface MovieRepositoryInterface extends BaseRepositoryInterface
{
    public function filter(
        array $filters,
        string $sortBy = 'title',
        string $order = 'asc',
    ): Collection;

    public function syncGenres(Movie $movie, array $genreIds): void;

    public function restore(int $id): Model;
}
