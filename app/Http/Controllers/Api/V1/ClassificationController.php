<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassificationRequest;
use App\Http\Requests\UpdateClassificationRequest;
use App\Http\Resources\ClassificationResource;
use App\Models\Classification;
use Illuminate\Http\JsonResponse;

class ClassificationController extends Controller
{
    public function index()
    {
        $classifications = Classification::orderBy('libelle')->paginate(50);
        return ClassificationResource::collection($classifications);
    }

    public function store(StoreClassificationRequest $request): JsonResponse
    {
        $this->authorize('create', [Classification::class]);
        $classification = Classification::create($request->validated());
        return response()->json($classification, 201);
    }

    public function show(Classification $classification): JsonResponse
    {
        return response()->json($classification);
    }

    public function update(UpdateClassificationRequest $request, Classification $classification): JsonResponse
    {
        $this->authorize('update', [$classification]);
        $classification->update($request->validated());
        return response()->json($classification);
    }

    public function destroy(Classification $classification): JsonResponse
    {
        $this->authorize('delete', [$classification]);
        $classification->delete();
        return response()->json(null, 204);
    }
}
