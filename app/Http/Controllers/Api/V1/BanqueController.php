<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Banque;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BanqueController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Banque::orderBy('nom')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:banques',
            'nom' => 'required|string|max:150',
            'pays_id' => 'nullable|exists:pays,id',
            'actif' => 'sometimes|boolean',
        ]);

        $banque = Banque::create($validated);
        return response()->json($banque, 201);
    }

    public function show(Banque $banque): JsonResponse
    {
        return response()->json($banque);
    }

    public function update(Request $request, Banque $banque): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:banques,code,' . $banque->id,
            'nom' => 'required|string|max:150',
            'pays_id' => 'nullable|exists:pays,id',
            'actif' => 'sometimes|boolean',
        ]);

        $banque->update($validated);
        return response()->json($banque);
    }

    public function destroy(Banque $banque): JsonResponse
    {
        $banque->delete();
        return response()->json(null, 204);
    }
}