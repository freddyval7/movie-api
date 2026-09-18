<?php

namespace App\Repositories\Eloquent;

use App\Models\Genre;
use App\Repositories\Contracts\GenreRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GenreRepository extends BaseRepository implements GenreRepositoryInterface
{
    private const SORTABLE = ['name', 'created_at', 'is_active'];

    /**
     * Create a new class instance.
     */
    public function __construct(Genre $model)
    {
        parent::__construct($model);
    }

    public function filter(
        array $filters,
        string $sortBy = 'name',
        string $order = 'asc',
    ): Collection {
        $sortBy = in_array($sortBy, self::SORTABLE) ? $sortBy : 'name';

        $order = strtolower($order) === 'desc' ? 'desc' : 'asc';

        return Genre::query()
            ->when(isset($filters['search']), function ($query) use ($filters) {
                return $query->where('name', 'ilike', "%{$filters['search']}%");
            })
            ->when(isset($filters['is_active']), function ($query) use ($filters) {
                return $query->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN));
            })
            ->orderBy($sortBy, $order)
            ->get();
    }

    public function findBySlugOrFail(string $slug): Model
    {
        return Genre::where('slug', $slug)->firstOrFail();
    }
}
