<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class EmployeController extends Controller
{
    public function index(): JsonResponse
    {
        $employes = Employe::with(['nationalite', 'banque'])
                             ->orderBy('nom')
                             ->paginate(50);
        return response()->json($employes);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
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
        ]);

        $employe = Employe::create($validated);
        return response()->json($employe, 201);
    }

    public function show(Employe $employe): JsonResponse
    {
        $employe->load(['nationalite', 'banque', 'affectations.navire', 'affectations.fonction']);
        return response()->json($employe);
    }

    public function update(Request $request, Employe $employe): JsonResponse
    {
        $validated = $request->validate([
            'matricule' => ['required', 'string', 'max:50', Rule::unique('employes')->ignore($employe->id)],
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
        ]);

        $employe->update($validated);
        return response()->json($employe);
    }

    public function destroy(Employe $employe): JsonResponse
    {
        $employe->delete();
        return response()->json(null, 204);
    }
}
