<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MovieFactory extends Factory
{
    protected $model = Movie::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'synopsis' => $this->faker->paragraph(),
            'year' => $this->faker->numberBetween(1980, 2024),
            'rating' => $this->faker->randomFloat(1, 0, 10),
            'director' => $this->faker->name(),
            'duration' => $this->faker->numberBetween(80, 180),
            'poster' => null,
            'is_active' => true,
        ];
    }
}
