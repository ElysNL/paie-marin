<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeRequest;
use App\Http\Requests\UpdateEmployeRequest;
use App\Http\Resources\EmployeResource;
use App\Models\Employe;
use Illuminate\Http\JsonResponse;

class EmployeController extends Controller
{
    public function index()
    {
        $employes = Employe::with(['nationalite', 'banque'])
                             ->orderBy('nom')
                             ->paginate(50);
        return EmployeResource::collection($employes);
    }

    public function store(StoreEmployeRequest $request): JsonResponse
    {
        $this->authorize('create', Employe::class);
        $employe = Employe::create($request->validated());
        return response()->json($employe, 201);
    }

    public function show(Employe $employe): JsonResponse
    {
        $this->authorize('view', $employe);
        $employe->load(['nationalite', 'banque', 'affectations.navire', 'affectations.fonction']);
        return response()->json($employe);
    }

    public function update(UpdateEmployeRequest $request, Employe $employe): JsonResponse
    {
        $this->authorize('update', $employe);
        $employe->update($request->validated());
        return response()->json($employe);
    }

    public function destroy(Employe $employe): JsonResponse
    {
        $this->authorize('delete', $employe);
        $employe->delete();
        return response()->json(null, 204);
    }
}
