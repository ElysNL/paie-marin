<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNavireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'armateur_id' => 'required|exists:armateurs,id',
            'compagnie_id' => 'nullable|exists:compagnies,id',
            'code' => 'required|string|max:20|unique:navires',
            'nom' => 'required|string|max:100',
            'immatriculation' => 'nullable|string|max:50',
            'pavillon_id' => 'nullable|exists:pays,id',
            'type' => 'nullable|string|max:50',
            'actif' => 'sometimes|boolean',
        ];
    }
}
