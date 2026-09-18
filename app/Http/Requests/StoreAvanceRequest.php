<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employe_id' => 'required|exists:employes,id',
            'date_avance' => 'required|date',
            'montant' => 'required|numeric|min:0',
            'devise_id' => 'nullable|exists:devises,id',
            'motif' => 'nullable|string|max:255',
        ];
    }
}
