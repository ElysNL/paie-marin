<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContratArmateurRequest;
use App\Http\Requests\UpdateContratArmateurRequest;
use App\Http\Resources\ContratArmateurResource;
use App\Models\ContratArmateur;
use Illuminate\Http\JsonResponse;

class ContratArmateurController extends Controller
{
    public function index()
    {
        $contrats = ContratArmateur::with(['armateur', 'devise'])
                                   ->orderBy('libelle')
                                   ->paginate(50);
        return ContratArmateurResource::collection($contrats);
    }

    public function store(StoreContratArmateurRequest $request): JsonResponse
    {
        $this->authorize('create', ContratArmateur::class);
        $contrat = ContratArmateur::create($request->validated());
        return response()->json($contrat, 201);
    }

    public function show(ContratArmateur $contratArmateur): JsonResponse
    {
        $this->authorize('view', $contratArmateur);
        $contratArmateur->load('armateur', 'devise', 'affectations');
        return response()->json($contratArmateur);
    }

    public function update(UpdateContratArmateurRequest $request, ContratArmateur $contratArmateur): JsonResponse
    {
        $this->authorize('update', $contratArmateur);
        $contratArmateur->update($request->validated());
        return response()->json($contratArmateur);
    }

    public function destroy(ContratArmateur $contratArmateur): JsonResponse
    {
        $this->authorize('delete', $contratArmateur);
        $contratArmateur->delete();
        return response()->json(null, 204);
    }
}
