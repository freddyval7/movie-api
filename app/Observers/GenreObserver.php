<?php

namespace App\Observers;

use App\Models\Genre;
use Illuminate\Support\Facades\Cache;

class GenreObserver
{
    /**
     * Handle the genre "created" event.
     */
    public function created(Genre $genre): void
    {
        $this->flush();
    }

    /**
     * Handle the genre "updated" event.
     */
    public function updated(Genre $genre): void
    {
        $this->flush();
    }

    /**
     * Handle the genre "deleted" event.
     */
    public function deleted(Genre $genre): void
    {
        $this->flush();
    }

    /**
     * Handle the genre "restored" event.
     */
    public function restored(Genre $genre): void
    {
        $this->flush();
    }

    private function flush(): void
    {
        Cache::flush();
    }
}
