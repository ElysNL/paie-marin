<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBanqueRequest;
use App\Http\Requests\UpdateBanqueRequest;
use App\Http\Resources\BanqueResource;
use App\Models\Banque;
use Illuminate\Http\JsonResponse;

class BanqueController extends Controller
{
    public function index()
    {
        return BanqueResource::collection(Banque::orderBy('nom')->get());
    }

    public function store(StoreBanqueRequest $request): JsonResponse
    {
        $this->authorize('create', [Banque::class]);
        $banque = Banque::create($request->validated());
        return response()->json($banque, 201);
    }

    public function show(Banque $banque): JsonResponse
    {
        return response()->json($banque);
    }

    public function update(UpdateBanqueRequest $request, Banque $banque): JsonResponse
    {
        $this->authorize('update', [$banque]);
        $banque->update($request->validated());
        return response()->json($banque);
    }

    public function destroy(Banque $banque): JsonResponse
    {
        $this->authorize('delete', [$banque]);
        $banque->delete();
        return response()->json(null, 204);
    }
}
