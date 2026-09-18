<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDelegationRequest;
use App\Http\Requests\UpdateDelegationRequest;
use App\Http\Resources\DelegationResource;
use App\Models\Delegation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DelegationController extends Controller
{
    public function index(Request $request)
    {
        $query = Delegation::with(['employe', 'devise'])
            ->orderBy('date_debut', 'desc');

        if ($request->filled('employe_id')) {
            $query->where('employe_id', $request->employe_id);
        }

        return DelegationResource::collection($query->paginate(20));
    }

    public function store(StoreDelegationRequest $request): JsonResponse
    {
        $this->authorize('create', Delegation::class);
        $validated = $request->validated();

        $validated['statut'] = $validated['statut'] ?? 'actif';

        $delegation = Delegation::create($validated);
        $delegation->load(['employe', 'devise']);

        return response()->json($delegation, 201);
    }

    public function show(Delegation $delegation): JsonResponse
    {
        $this->authorize('view', $delegation);
        $delegation->load(['employe', 'devise', 'bulletinsDelegations']);
        return response()->json($delegation);
    }

    public function update(UpdateDelegationRequest $request, Delegation $delegation): JsonResponse
    {
        $this->authorize('update', $delegation);
        $delegation->update($request->validated());
        $delegation->load(['employe', 'devise']);

        return response()->json($delegation);
    }

    public function destroy(Delegation $delegation): JsonResponse
    {
        $this->authorize('delete', $delegation);
        $delegation->delete();
        return response()->json(null, 204);
    }
}
