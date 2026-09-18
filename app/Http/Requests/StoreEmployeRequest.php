<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'matricule' => 'required|string|max:50|unique:employes',
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:100',
            'nationalite_id' => 'nullable|exists:pays,id',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'cin' => 'nullable|string|max:50',
            'banque_id' => 'nullable|exists:banques,id',
            'compte_bancaire' => 'nullable|string|max:50',
            'date_embauche' => 'nullable|date',
            'nbre_charges' => 'nullable|integer|min:0',
            'actif' => 'sometimes|boolean',
        ];
    }
}
