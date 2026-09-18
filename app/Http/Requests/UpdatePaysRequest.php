<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaysRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'size:3', Rule::unique('pays')->ignore($this->route('pays')->id)],
            'nom' => 'required|string|max:100',
            'nationalite' => 'nullable|string|max:100',
            'actif' => 'sometimes|boolean',
        ];
    }
}
