<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ChirpRequest extends FormRequest
{
    /**
     * Authorization is handled by ChirpPolicy in the controller.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Shared validation rules for creating and updating chirps.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'message' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please enter a title.',
            'title.max' => 'Title cannot exceed 100 characters.',

            'message.required' => 'Please write something to chirp.',
            'message.max' => 'Chirps must be 255 characters or less.',
        ];
    }
}