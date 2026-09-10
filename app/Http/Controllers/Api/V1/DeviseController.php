<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Devise;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DeviseController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Devise::actif()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:3|unique:devises',
            'libelle' => 'required|string|max:50',
            'symbole' => 'nullable|string|max:10',
            'nb_decimales' => 'sometimes|integer|min:0|max:4',
            'actif' => 'sometimes|boolean',
        ]);

        $devise = Devise::create($validated);
        return response()->json($devise, 201);
    }

    public function show(Devise $devise): JsonResponse
    {
        return response()->json($devise);
    }

    public function update(Request $request, Devise $devise): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:3|unique:devises,code,' . $devise->id,
            'libelle' => 'required|string|max:50',
            'symbole' => 'nullable|string|max:10',
            'nb_decimales' => 'sometimes|integer|min:0|max:4',
            'actif' => 'sometimes|boolean',
        ]);

        $devise->update($validated);
        return response()->json($devise);
    }

    public function destroy(Devise $devise): JsonResponse
    {
        $devise->delete();
        return response()->json(null, 204);
    }
}
