<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AffectationMarin;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class AffectationController extends Controller
{
    public function index(): JsonResponse
    {
        $affectations = AffectationMarin::with(['employe', 'navire', 'fonction', 'contratArmateur', 'devise'])
                                        ->paginate(50);
        return response()->json($affectations);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employe_id' => 'required|exists:employes,id',
            'navire_id' => 'required|exists:navires,id',
            'fonction_id' => 'required|exists:fonctions,id',
            'contrat_armateur_id' => 'required|exists:contrats_armateur,id',
            'date_embt' => 'required|date',
            'date_debt' => 'nullable|date|after_or_equal:date_embt',
            'taux_journalier' => 'required|numeric|min:0',
            'devise_id' => 'required|exists:devises,id',
            'statut' => 'sometimes|in:actif,termine,annule',
        ]);

        $affectation = AffectationMarin::create($validated);
        return response()->json($affectation, 201);
    }

    public function show(AffectationMarin $affectation): JsonResponse
    {
        $affectation->load(['employe', 'navire', 'fonction', 'contratArmateur', 'devise']);
        return response()->json($affectation);
    }

    public function update(Request $request, AffectationMarin $affectation): JsonResponse
    {
        $validated = $request->validate([
            'employe_id' => 'sometimes|exists:employes,id',
            'navire_id' => 'sometimes|exists:navires,id',
            'fonction_id' => 'sometimes|exists:fonctions,id',
            'contrat_armateur_id' => 'sometimes|exists:contrats_armateur,id',
            'date_embt' => 'sometimes|date',
            'date_debt' => 'nullable|date|after_or_equal:date_embt',
            'taux_journalier' => 'sometimes|numeric|min:0',
            'devise_id' => 'sometimes|exists:devises,id',
            'statut' => 'sometimes|in:actif,termine,annule',
        ]);

        $affectation->update($validated);
        return response()->json($affectation);
    }

    public function destroy(AffectationMarin $affectation): JsonResponse
    {
        $affectation->delete();
        return response()->json(null, 204);
    }
}
