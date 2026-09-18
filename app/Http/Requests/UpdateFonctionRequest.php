<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFonctionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('fonctions')->ignore($this->route('fonction')->id)],
            'libelle' => 'required|string|max:100',
            'description' => 'nullable|string',
            'actif' => 'sometimes|boolean',
        ];
    }
}
