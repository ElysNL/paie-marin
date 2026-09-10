<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Pays;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaysController extends Controller
{
    public function index(): JsonResponse
    {
        $pays = Pays::actif()->orderBy('nom')->get();
        return response()->json($pays);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|size:3|unique:pays',
            'nom' => 'required|string|max:100',
            'nationalite' => 'nullable|string|max:100',
            'actif' => 'sometimes|boolean',
        ]);

        $pays = Pays::create($validated);
        return response()->json($pays, 201);
    }

    public function show(Pays $pays): JsonResponse
    {
        return response()->json($pays);
    }

    public function update(Request $request, Pays $pays): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|size:3|unique:pays,code,' . $pays->id,
            'nom' => 'required|string|max:100',
            'nationalite' => 'nullable|string|max:100',
            'actif' => 'sometimes|boolean',
        ]);

        $pays->update($validated);
        return response()->json($pays);
    }

    public function destroy(Pays $pays): JsonResponse
    {
        $pays->delete();
        return response()->json(null, 204);
    }
}
