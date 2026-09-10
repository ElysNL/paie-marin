<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Delegation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DelegationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Delegation::with(['employe', 'devise'])
            ->orderBy('date_debut', 'desc');

        if ($request->has('employe_id')) {
            $query->where('employe_id', $request->employe_id);
        }

        return response()->json($query->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employe_id' => 'required|exists:employes,id',
            'beneficiaire' => 'required|string|max:150',
            'montant' => 'required|numeric|min:0',
            'devise_id' => 'nullable|exists:devises,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'frequence' => ['nullable', Rule::in(['mensuel', 'ponctuel'])],
            'statut' => ['nullable', Rule::in(['actif', 'termine', 'annule'])],
        ]);

        $validated['statut'] = $validated['statut'] ?? 'actif';

        $delegation = Delegation::create($validated);
        $delegation->load(['employe', 'devise']);

        return response()->json($delegation, 201);
    }

    public function show(Delegation $delegation): JsonResponse
    {
        $delegation->load(['employe', 'devise', 'bulletinsDelegations']);
        return response()->json($delegation);
    }

    public function update(Request $request, Delegation $delegation): JsonResponse
    {
        $validated = $request->validate([
            'employe_id' => 'required|exists:employes,id',
            'beneficiaire' => 'required|string|max:150',
            'montant' => 'required|numeric|min:0',
            'devise_id' => 'nullable|exists:devises,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'frequence' => ['nullable', Rule::in(['mensuel', 'ponctuel'])],
            'statut' => ['nullable', Rule::in(['actif', 'termine', 'annule'])],
        ]);

        $delegation->update($validated);
        $delegation->load(['employe', 'devise']);

        return response()->json($delegation);
    }

    public function destroy(Delegation $delegation): JsonResponse
    {
        $delegation->delete();
        return response()->json(null, 204);
    }
}