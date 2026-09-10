<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Armateur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class ArmateurController extends Controller
{
    public function index(): JsonResponse
    {
        $armateurs = Armateur::with('pays')->paginate(50);
        return response()->json($armateurs);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:armateurs',
            'nom' => 'required|string|max:100',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string',
            'email' => 'nullable|email|max:100',
            'pays_id' => 'nullable|exists:pays,id',
            'actif' => 'sometimes|boolean',
        ]);

        $armateur = Armateur::create($validated);
        return response()->json($armateur, 201);
    }

    public function show(Armateur $armateur): JsonResponse
    {
        $armateur->load('pays', 'navires', 'contrats');
        return response()->json($armateur);
    }

    public function update(Request $request, Armateur $armateur): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('armateurs')->ignore($armateur->id)],
            'nom' => 'required|string|max:100',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string',
            'email' => 'nullable|email|max:100',
            'pays_id' => 'nullable|exists:pays,id',
            'actif' => 'sometimes|boolean',
        ]);

        $armateur->update($validated);
        return response()->json($armateur);
    }

    public function destroy(Armateur $armateur): JsonResponse
    {
        $armateur->delete();
        return response()->json(null, 204);
    }
}
