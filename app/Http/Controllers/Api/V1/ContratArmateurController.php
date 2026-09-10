<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ContratArmateur;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ContratArmateurController extends Controller
{
    public function index(): JsonResponse
    {
        $contrats = ContratArmateur::with(['armateur', 'devise'])
                                   ->orderBy('libelle')
                                   ->paginate(50);
        return response()->json($contrats);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'armateur_id' => 'required|exists:armateurs,id',
            'code' => 'required|string|max:20|unique:contrat_armateurs',
            'libelle' => 'required|string|max:100',
            'devise_id' => 'required|exists:devises,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'taux_base' => 'nullable|numeric|min:0',
            'conditions' => 'nullable|string',
            'actif' => 'sometimes|boolean',
        ]);

        $contrat = ContratArmateur::create($validated);
        return response()->json($contrat, 201);
    }

    public function show(ContratArmateur $contratArmateur): JsonResponse
    {
        $contratArmateur->load('armateur', 'devise', 'affectations');
        return response()->json($contratArmateur);
    }

    public function update(Request $request, ContratArmateur $contratArmateur): JsonResponse
    {
        $validated = $request->validate([
            'armateur_id' => 'sometimes|exists:armateurs,id',
            'code' => ['sometimes', 'string', 'max:20', Rule::unique('contrat_armateurs')->ignore($contratArmateur->id)],
            'libelle' => 'sometimes|string|max:100',
            'devise_id' => 'sometimes|exists:devises,id',
            'date_debut' => 'sometimes|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'taux_base' => 'nullable|numeric|min:0',
            'conditions' => 'nullable|string',
            'actif' => 'sometimes|boolean',
        ]);

        $contratArmateur->update($validated);
        return response()->json($contratArmateur);
    }

    public function destroy(ContratArmateur $contratArmateur): JsonResponse
    {
        $contratArmateur->delete();
        return response()->json(null, 204);
    }
}
