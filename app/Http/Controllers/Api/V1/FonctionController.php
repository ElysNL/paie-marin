<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fonction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FonctionController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Fonction::actif()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:fonctions',
            'libelle' => 'required|string|max:100',
            'description' => 'nullable|string',
            'actif' => 'sometimes|boolean',
        ]);

        $fonction = Fonction::create($validated);
        return response()->json($fonction, 201);
    }

    public function show(Fonction $fonction): JsonResponse
    {
        return response()->json($fonction);
    }

    public function update(Request $request, Fonction $fonction): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:fonctions,code,' . $fonction->id,
            'libelle' => 'required|string|max:100',
            'description' => 'nullable|string',
            'actif' => 'sometimes|boolean',
        ]);

        $fonction->update($validated);
        return response()->json($fonction);
    }

    public function destroy(Fonction $fonction): JsonResponse
    {
        $fonction->delete();
        return response()->json(null, 204);
    }
}
