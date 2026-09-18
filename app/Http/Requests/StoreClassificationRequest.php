<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:20|unique:classifications',
            'libelle' => 'required|string|max:100',
            'description' => 'nullable|string',
            'actif' => 'sometimes|boolean',
        ];
    }
}
