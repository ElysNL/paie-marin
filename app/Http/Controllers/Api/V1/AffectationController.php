<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAffectationRequest;
use App\Http\Requests\UpdateAffectationRequest;
use App\Http\Resources\AffectationResource;
use App\Models\AffectationMarin;
use Illuminate\Http\JsonResponse;

class AffectationController extends Controller
{
    public function index()
    {
        $affectations = AffectationMarin::with(['employe', 'navire', 'fonction', 'contratArmateur', 'devise'])
                                        ->paginate(50);
        return AffectationResource::collection($affectations);
    }

    public function store(StoreAffectationRequest $request): JsonResponse
    {
        $this->authorize('create', AffectationMarin::class);
        $affectation = AffectationMarin::create($request->validated());
        return response()->json($affectation, 201);
    }

    public function show(AffectationMarin $affectation): JsonResponse
    {
        $this->authorize('view', $affectation);
        $affectation->load(['employe', 'navire', 'fonction', 'contratArmateur', 'devise']);
        return response()->json($affectation);
    }

    public function update(UpdateAffectationRequest $request, AffectationMarin $affectation): JsonResponse
    {
        $this->authorize('update', $affectation);
        $affectation->update($request->validated());
        return response()->json($affectation);
    }

    public function destroy(AffectationMarin $affectation): JsonResponse
    {
        $this->authorize('delete', $affectation);
        $affectation->delete();
        return response()->json(null, 204);
    }
}
