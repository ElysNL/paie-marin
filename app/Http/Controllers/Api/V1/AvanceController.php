<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Avance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AvanceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Avance::with(['employe', 'devise'])
            ->orderBy('date_avance', 'desc');

        if ($request->has('employe_id')) {
            $query->where('employe_id', $request->employe_id);
        }
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        return response()->json($query->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employe_id' => 'required|exists:employes,id',
            'date_avance' => 'required|date',
            'montant' => 'required|numeric|min:0',
            'devise_id' => 'nullable|exists:devises,id',
            'motif' => 'nullable|string|max:255',
        ]);

        $validated['statut'] = 'en_cours';
        $validated['solde'] = $validated['montant'];

        $avance = Avance::create($validated);
        $avance->load(['employe', 'devise']);

        return response()->json($avance, 201);
    }

    public function show(Avance $avance): JsonResponse
    {
        $avance->load(['employe', 'devise', 'remboursements']);
        return response()->json($avance);
    }

    public function update(Request $request, Avance $avance): JsonResponse
    {
        $validated = $request->validate([
            'employe_id' => 'required|exists:employes,id',
            'date_avance' => 'required|date',
            'montant' => 'required|numeric|min:0',
            'devise_id' => 'nullable|exists:devises,id',
            'motif' => 'nullable|string|max:255',
            'statut' => ['sometimes', Rule::in(['en_cours', 'remboursee', 'annulee'])],
        ]);

        if (isset($validated['montant']) && $validated['montant'] != $avance->montant) {
            $solde = $avance->solde - ($avance->montant - $validated['montant']);
            $validated['solde'] = max(0, $solde);
        }

        $avance->update($validated);
        $avance->load(['employe', 'devise']);

        return response()->json($avance);
    }

    public function destroy(Avance $avance): JsonResponse
    {
        $avance->delete();
        return response()->json(null, 204);
    }
}