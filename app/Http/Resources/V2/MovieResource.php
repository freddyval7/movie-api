<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'full_title' => "{$this->title} ({$this->year})",
            'slug' => $this->slug,
            'synopsis' => $this->synopsis,
            'details' => [
                'year' => $this->year,
                'rating' => $this->rating,
                'director' => $this->director,
                'duration' => $this->duration,
            ],
            'genres' => $this->whenLoaded('genres', fn () => $this->genres->pluck('name')),
            'poster_url' => $this->poster ? asset('storage/'.$this->poster) : null,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
