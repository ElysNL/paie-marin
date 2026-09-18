<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContratArmateurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'armateur_id' => 'sometimes|exists:armateurs,id',
            'code' => ['sometimes', 'string', 'max:20', Rule::unique('contrat_armateurs')->ignore($this->route('contrat_armateur')->id)],
            'libelle' => 'sometimes|string|max:100',
            'devise_id' => 'sometimes|exists:devises,id',
            'date_debut' => 'sometimes|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'taux_base' => 'nullable|numeric|min:0',
            'conditions' => 'nullable|string',
            'actif' => 'sometimes|boolean',
        ];
    }
}
