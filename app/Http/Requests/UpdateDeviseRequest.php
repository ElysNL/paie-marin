<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDeviseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:3', Rule::unique('devises')->ignore($this->route('devise')->id)],
            'libelle' => 'required|string|max:50',
            'symbole' => 'nullable|string|max:10',
            'nb_decimales' => 'sometimes|integer|min:0|max:4',
            'actif' => 'sometimes|boolean',
        ];
    }
}
