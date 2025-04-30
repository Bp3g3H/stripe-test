<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id', // Ensure the category exists
            'price' => 'sometimes|required|numeric|min:0', // Price must be a positive number
            'description' => 'nullable|string|max:1000', // Optional description
            'stock' => 'sometimes|required|integer|min:0', // Stock must be a non-negative integer
            'image_url' => 'nullable|url', // Optional valid URL for the image
        ];
    }
}
