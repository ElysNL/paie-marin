<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArmateurRequest;
use App\Http\Requests\UpdateArmateurRequest;
use App\Http\Resources\ArmateurResource;
use App\Models\Armateur;
use Illuminate\Http\JsonResponse;

class ArmateurController extends Controller
{
    public function index()
    {
        $armateurs = Armateur::with('pays')->paginate(50);
        return ArmateurResource::collection($armateurs);
    }

    public function store(StoreArmateurRequest $request): JsonResponse
    {
        $this->authorize('create', Armateur::class);
        $armateur = Armateur::create($request->validated());
        return response()->json($armateur, 201);
    }

    public function show(Armateur $armateur): JsonResponse
    {
        $this->authorize('view', $armateur);
        $armateur->load('pays', 'navires', 'contrats');
        return response()->json($armateur);
    }

    public function update(UpdateArmateurRequest $request, Armateur $armateur): JsonResponse
    {
        $this->authorize('update', $armateur);
        $armateur->update($request->validated());
        return response()->json($armateur);
    }

    public function destroy(Armateur $armateur): JsonResponse
    {
        $this->authorize('delete', $armateur);
        $armateur->delete();
        return response()->json(null, 204);
    }
}
