<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAffectationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employe_id' => 'sometimes|exists:employes,id',
            'navire_id' => 'sometimes|exists:navires,id',
            'fonction_id' => 'sometimes|exists:fonctions,id',
            'contrat_armateur_id' => 'sometimes|exists:contrats_armateur,id',
            'date_embt' => 'sometimes|date',
            'date_debt' => 'nullable|date|after_or_equal:date_embt',
            'taux_journalier' => 'sometimes|numeric|min:0',
            'devise_id' => 'sometimes|exists:devises,id',
            'statut' => 'sometimes|in:actif,termine,annule',
        ];
    }
}
