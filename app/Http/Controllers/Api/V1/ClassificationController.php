<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Classification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ClassificationController extends Controller
{
    public function index(): JsonResponse
    {
        $classifications = Classification::orderBy('libelle')->paginate(50);
        return response()->json($classifications);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:classifications',
            'libelle' => 'required|string|max:100',
            'description' => 'nullable|string',
            'actif' => 'sometimes|boolean',
        ]);

        $classification = Classification::create($validated);
        return response()->json($classification, 201);
    }

    public function show(Classification $classification): JsonResponse
    {
        return response()->json($classification);
    }

    public function update(Request $request, Classification $classification): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('classifications')->ignore($classification->id)],
            'libelle' => 'required|string|max:100',
            'description' => 'nullable|string',
            'actif' => 'sometimes|boolean',
        ]);

        $classification->update($validated);
        return response()->json($classification);
    }

    public function destroy(Classification $classification): JsonResponse
    {
        $classification->delete();
        return response()->json(null, 204);
    }
}
