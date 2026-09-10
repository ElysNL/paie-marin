<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Navire;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class NavireController extends Controller
{
    public function index(): JsonResponse
    {
        $navires = Navire::with(['armateur', 'compagnie', 'pavillon'])
                         ->paginate(50);
        return response()->json($navires);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'armateur_id' => 'required|exists:armateurs,id',
            'compagnie_id' => 'nullable|exists:compagnies,id',
            'code' => 'required|string|max:20|unique:navires',
            'nom' => 'required|string|max:100',
            'immatriculation' => 'nullable|string|max:50',
            'pavillon_id' => 'nullable|exists:pays,id',
            'type' => 'nullable|string|max:50',
            'actif' => 'sometimes|boolean',
        ]);

        $navire = Navire::create($validated);
        return response()->json($navire, 201);
    }

    public function show(Navire $navire): JsonResponse
    {
        $navire->load(['armateur', 'compagnie', 'pavillon', 'affectations.employe']);
        return response()->json($navire);
    }

    public function update(Request $request, Navire $navire): JsonResponse
    {
        $validated = $request->validate([
            'armateur_id' => 'required|exists:armateurs,id',
            'compagnie_id' => 'nullable|exists:compagnies,id',
            'code' => ['required', 'string', 'max:20', Rule::unique('navires')->ignore($navire->id)],
            'nom' => 'required|string|max:100',
            'immatriculation' => 'nullable|string|max:50',
            'pavillon_id' => 'nullable|exists:pays,id',
            'type' => 'nullable|string|max:50',
            'actif' => 'sometimes|boolean',
        ]);

        $navire->update($validated);
        return response()->json($navire);
    }

    public function destroy(Navire $navire): JsonResponse
    {
        $navire->delete();
        return response()->json(null, 204);
    }
}
