<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'paie']);
    }

    public function rules(): array
    {
        return [
            'num_paie' => 'required|string|max:20|unique:paies',
            'libelle' => 'required|string|max:100',
            'periode' => 'required|string|max:20',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ];
    }
}
