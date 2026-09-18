<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaysRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|size:3|unique:pays',
            'nom' => 'required|string|max:100',
            'nationalite' => 'nullable|string|max:100',
            'actif' => 'sometimes|boolean',
        ];
    }
}
