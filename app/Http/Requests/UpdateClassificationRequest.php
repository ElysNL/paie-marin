<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClassificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('classifications')->ignore($this->route('classification')->id)],
            'libelle' => 'required|string|max:100',
            'description' => 'nullable|string',
            'actif' => 'sometimes|boolean',
        ];
    }
}
