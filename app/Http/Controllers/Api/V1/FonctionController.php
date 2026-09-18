<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFonctionRequest;
use App\Http\Requests\UpdateFonctionRequest;
use App\Http\Resources\FonctionResource;
use App\Models\Fonction;
use Illuminate\Http\JsonResponse;

class FonctionController extends Controller
{
    public function index()
    {
        return FonctionResource::collection(Fonction::actif()->get());
    }

    public function store(StoreFonctionRequest $request): JsonResponse
    {
        $this->authorize('create', [Fonction::class]);
        $fonction = Fonction::create($request->validated());
        return response()->json($fonction, 201);
    }

    public function show(Fonction $fonction): JsonResponse
    {
        return response()->json($fonction);
    }

    public function update(UpdateFonctionRequest $request, Fonction $fonction): JsonResponse
    {
        $this->authorize('update', [$fonction]);
        $fonction->update($request->validated());
        return response()->json($fonction);
    }

    public function destroy(Fonction $fonction): JsonResponse
    {
        $this->authorize('delete', [$fonction]);
        $fonction->delete();
        return response()->json(null, 204);
    }
}
