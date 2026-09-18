<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeviseRequest;
use App\Http\Requests\UpdateDeviseRequest;
use App\Http\Resources\DeviseResource;
use App\Models\Devise;
use Illuminate\Http\JsonResponse;

class DeviseController extends Controller
{
    public function index()
    {
        return DeviseResource::collection(Devise::actif()->get());
    }

    public function store(StoreDeviseRequest $request): JsonResponse
    {
        $this->authorize('create', [Devise::class]);
        $devise = Devise::create($request->validated());
        return response()->json($devise, 201);
    }

    public function show(Devise $devise): JsonResponse
    {
        return response()->json($devise);
    }

    public function update(UpdateDeviseRequest $request, Devise $devise): JsonResponse
    {
        $this->authorize('update', [$devise]);
        $devise->update($request->validated());
        return response()->json($devise);
    }

    public function destroy(Devise $devise): JsonResponse
    {
        $this->authorize('delete', [$devise]);
        $devise->delete();
        return response()->json(null, 204);
    }
}
