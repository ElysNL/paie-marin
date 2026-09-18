<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAffectationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employe_id' => 'required|exists:employes,id',
            'navire_id' => 'required|exists:navires,id',
            'fonction_id' => 'required|exists:fonctions,id',
            'contrat_armateur_id' => 'required|exists:contrats_armateur,id',
            'date_embt' => 'required|date',
            'date_debt' => 'nullable|date|after_or_equal:date_embt',
            'taux_journalier' => 'required|numeric|min:0',
            'devise_id' => 'required|exists:devises,id',
            'statut' => 'sometimes|in:actif,termine,annule',
        ];
    }
}
