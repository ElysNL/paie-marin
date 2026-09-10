<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Compagnie;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class CompagnieController extends Controller
{
    public function index(): JsonResponse
    {
        $compagnies = Compagnie::with('pays')->orderBy('nom')->paginate(50);
        return response()->json($compagnies);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:compagnies',
            'nom' => 'required|string|max:100',
            'pays_id' => 'nullable|exists:pays,id',
            'actif' => 'sometimes|boolean',
        ]);

        $compagnie = Compagnie::create($validated);
        return response()->json($compagnie, 201);
    }

    public function show(Compagnie $compagnie): JsonResponse
    {
        $compagnie->load('pays', 'navires');
        return response()->json($compagnie);
    }

    public function update(Request $request, Compagnie $compagnie): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('compagnies')->ignore($compagnie->id)],
            'nom' => 'required|string|max:100',
            'pays_id' => 'nullable|exists:pays,id',
            'actif' => 'sometimes|boolean',
        ]);

        $compagnie->update($validated);
        return response()->json($compagnie);
    }

    public function destroy(Compagnie $compagnie): JsonResponse
    {
        $compagnie->delete();
        return response()->json(null, 204);
    }
}
