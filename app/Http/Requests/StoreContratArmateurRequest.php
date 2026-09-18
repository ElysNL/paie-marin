<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContratArmateurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'armateur_id' => 'required|exists:armateurs,id',
            'code' => 'required|string|max:20|unique:contrat_armateurs',
            'libelle' => 'required|string|max:100',
            'devise_id' => 'required|exists:devises,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'taux_base' => 'nullable|numeric|min:0',
            'conditions' => 'nullable|string',
            'actif' => 'sometimes|boolean',
        ];
    }
}
