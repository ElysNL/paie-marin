<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDelegationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employe_id' => 'required|exists:employes,id',
            'beneficiaire' => 'required|string|max:150',
            'montant' => 'required|numeric|min:0',
            'devise_id' => 'nullable|exists:devises,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'frequence' => ['nullable', Rule::in(['mensuel', 'ponctuel'])],
            'statut' => ['nullable', Rule::in(['actif', 'termine', 'annule'])],
        ];
    }
}
