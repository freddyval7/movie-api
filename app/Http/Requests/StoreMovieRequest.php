<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'unique:movies,title'],
            'year' => ['required', 'integer', 'min:1888', 'max:2100'],
            'synopsis' => ['nullable', 'string', 'max:10000'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'director' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'poster' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'genre_ids' => ['nullable', 'array'],
            'genre_ids.*' => ['integer', 'exists:genres,id'],
        ];
    }
}
