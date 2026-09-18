<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaieRequest extends FormRequest
{
    public function authorize(): bool
    {
        $paie = $this->route('paie');
        return $paie
            && in_array($paie->statut, ['brouillon', 'calcule'])
            && in_array($this->user()?->role, ['admin', 'paie']);
    }

    public function rules(): array
    {
        return [
            'num_paie' => ['required', 'string', 'max:20', Rule::unique('paies')->ignore($this->route('paie')->id)],
            'libelle' => 'required|string|max:100',
            'periode' => 'required|string|max:20',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ];
    }
}
