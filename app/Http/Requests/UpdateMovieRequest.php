<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255', Rule::unique('movies', 'title')->ignore($this->id)],
            'year' => ['sometimes', 'integer', 'min:1888', 'max:2100'],
            'synopsis' => ['sometimes', 'string', 'max:10000'],
            'rating' => ['sometimes', 'numeric', 'min:0', 'max:10'],
            'director' => ['sometimes', 'string', 'max:255'],
            'duration' => ['sometimes', 'integer', 'min:1'],
            'poster' => ['sometimes', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'genre_ids' => ['sometimes', 'array'],
            'genre_ids.*' => ['integer', 'exists:genres,id'],
        ];
    }
}
