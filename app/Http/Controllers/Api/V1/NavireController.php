<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNavireRequest;
use App\Http\Requests\UpdateNavireRequest;
use App\Http\Resources\NavireResource;
use App\Models\Navire;
use Illuminate\Http\JsonResponse;

class NavireController extends Controller
{
    public function index()
    {
        $navires = Navire::with(['armateur', 'compagnie', 'pavillon'])
                         ->paginate(50);
        return NavireResource::collection($navires);
    }

    public function store(StoreNavireRequest $request): JsonResponse
    {
        $this->authorize('create', Navire::class);
        $navire = Navire::create($request->validated());
        return response()->json($navire, 201);
    }

    public function show(Navire $navire): JsonResponse
    {
        $this->authorize('view', $navire);
        $navire->load(['armateur', 'compagnie', 'pavillon', 'affectations.employe']);
        return response()->json($navire);
    }

    public function update(UpdateNavireRequest $request, Navire $navire): JsonResponse
    {
        $this->authorize('update', $navire);
        $navire->update($request->validated());
        return response()->json($navire);
    }

    public function destroy(Navire $navire): JsonResponse
    {
        $this->authorize('delete', $navire);
        $navire->delete();
        return response()->json(null, 204);
    }
}
