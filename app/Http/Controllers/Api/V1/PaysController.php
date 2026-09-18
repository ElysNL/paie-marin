<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaysRequest;
use App\Http\Requests\UpdatePaysRequest;
use App\Http\Resources\PaysResource;
use App\Models\Pays;
use Illuminate\Http\JsonResponse;

class PaysController extends Controller
{
    public function index()
    {
        $pays = Pays::actif()->orderBy('nom')->get();
        return PaysResource::collection($pays);
    }

    public function store(StorePaysRequest $request): JsonResponse
    {
        $this->authorize('create', [Pays::class]);
        $pays = Pays::create($request->validated());
        return response()->json($pays, 201);
    }

    public function show(Pays $pays): JsonResponse
    {
        return response()->json($pays);
    }

    public function update(UpdatePaysRequest $request, Pays $pays): JsonResponse
    {
        $this->authorize('update', [$pays]);
        $pays->update($request->validated());
        return response()->json($pays);
    }

    public function destroy(Pays $pays): JsonResponse
    {
        $this->authorize('delete', [$pays]);
        $pays->delete();
        return response()->json(null, 204);
    }
}
