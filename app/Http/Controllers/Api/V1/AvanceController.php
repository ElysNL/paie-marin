<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAvanceRequest;
use App\Http\Requests\UpdateAvanceRequest;
use App\Http\Resources\AvanceResource;
use App\Models\Avance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AvanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Avance::with(['employe', 'devise'])
            ->orderBy('date_avance', 'desc');

        if ($request->filled('employe_id')) {
            $query->where('employe_id', $request->employe_id);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        return AvanceResource::collection($query->paginate(20));
    }

    public function store(StoreAvanceRequest $request): JsonResponse
    {
        $this->authorize('create', Avance::class);
        $validated = $request->validated();

        $validated['statut'] = 'en_cours';
        $validated['solde'] = $validated['montant'];

        $avance = Avance::create($validated);
        $avance->load(['employe', 'devise']);

        return response()->json($avance, 201);
    }

    public function show(Avance $avance): JsonResponse
    {
        $this->authorize('view', $avance);
        $avance->load(['employe', 'devise', 'remboursements']);
        return response()->json($avance);
    }

    public function update(UpdateAvanceRequest $request, Avance $avance): JsonResponse
    {
        $this->authorize('update', $avance);
        $validated = $request->validated();

        if (isset($validated['montant']) && $validated['montant'] != $avance->montant) {
            $solde = $avance->solde + ($validated['montant'] - $avance->montant);
            $validated['solde'] = max(0, $solde);
        }

        $avance->update($validated);
        $avance->load(['employe', 'devise']);

        return response()->json($avance);
    }

    public function destroy(Avance $avance): JsonResponse
    {
        $this->authorize('delete', $avance);
        $avance->delete();
        return response()->json(null, 204);
    }
}
