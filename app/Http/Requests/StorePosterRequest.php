<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePosterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'poster' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'poster.required' => 'El poster es obligatorio',
            'poster.image' => 'El poster debe ser una imagen',
            'poster.mimes' => 'El poster debe ser un archivo JPEG, PNG, JPG o WEBP',
            'poster.max' => 'El poster no debe ser mayor a 2MB',
        ];
    }
}
