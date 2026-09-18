<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompagnieRequest;
use App\Http\Requests\UpdateCompagnieRequest;
use App\Http\Resources\CompagnieResource;
use App\Models\Compagnie;
use Illuminate\Http\JsonResponse;

class CompagnieController extends Controller
{
    public function index()
    {
        $compagnies = Compagnie::with('pays')->orderBy('nom')->paginate(50);
        return CompagnieResource::collection($compagnies);
    }

    public function store(StoreCompagnieRequest $request): JsonResponse
    {
        $this->authorize('create', [Compagnie::class]);
        $compagnie = Compagnie::create($request->validated());
        return response()->json($compagnie, 201);
    }

    public function show(Compagnie $compagnie): JsonResponse
    {
        $compagnie->load('pays', 'navires');
        return response()->json($compagnie);
    }

    public function update(UpdateCompagnieRequest $request, Compagnie $compagnie): JsonResponse
    {
        $this->authorize('update', [$compagnie]);
        $compagnie->update($request->validated());
        return response()->json($compagnie);
    }

    public function destroy(Compagnie $compagnie): JsonResponse
    {
        $this->authorize('delete', [$compagnie]);
        $compagnie->delete();
        return response()->json(null, 204);
    }
}
