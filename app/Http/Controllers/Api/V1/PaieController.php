<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaieRequest;
use App\Http\Requests\UpdatePaieRequest;
use App\Http\Resources\PaieResource;
use App\Jobs\CalculerPaieJob;
use App\Models\Paie;
use App\Models\AffectationMarin;
use App\Services\CalculateurDePaie;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class PaieController extends Controller
{
    protected $calculator;

    public function __construct(CalculateurDePaie $calculator)
    {
        $this->calculator = $calculator;
    }

    public function index()
    {
        $this->authorize('viewAny', Paie::class);
        $paies = Paie::withCount('bulletins')->orderBy('created_at', 'desc')->paginate(50);
        return PaieResource::collection($paies);
    }

    public function store(StorePaieRequest $request): JsonResponse
    {
        $paie = Paie::create($request->validated() + ['statut' => 'brouillon']);
        return response()->json(new PaieResource($paie), 201);
    }

    public function show(Paie $paie): JsonResponse
    {
        $paie->load(['bulletins.employe', 'bulletins.navire']);
        return response()->json(new PaieResource($paie));
    }

    public function eligibles(Paie $paie): JsonResponse
    {
        $affectations = AffectationMarin::pourPeriode($paie->date_debut, $paie->date_fin)
                                        ->actif()
                                        ->with(['employe', 'navire', 'fonction', 'contratArmateur.devise'])
                                        ->get();

        return response()->json($affectations);
    }

    public function update(UpdatePaieRequest $request, Paie $paie): JsonResponse
    {
        $paie->update($request->validated());
        return response()->json(new PaieResource($paie->fresh()));
    }

    public function destroy(Paie $paie): JsonResponse
    {
        $this->authorize('delete', $paie);
        $paie->delete();
        return response()->json(null, 204);
    }

    /**
     * Calcul asynchrone des bulletins (dispatch job).
     */
    public function calculer(Paie $paie, Request $request): JsonResponse
    {
        $this->authorize('calculer', $paie);

        $validated = $request->validate([
            'navire_id' => 'nullable|exists:navires,id',
            'employe_id' => 'nullable|exists:employes,id',
        ]);

        if ($paie->statut_calcul === 'en_cours') {
            return response()->json(['message' => 'Un calcul est déjà en cours pour cette paie.'], 422);
        }

        $job = Bus::dispatch(new CalculerPaieJob(
            $paie->id,
            $validated['navire_id'] ?? null,
            $validated['employe_id'] ?? null,
        ));

        return response()->json([
            'message' => 'Calcul lancé.',
            'job_id' => $job->job->getId() ?? null,
        ], 202);
    }

    /**
     * Statut du calcul en cours.
     */
    public function statutCalcul(Paie $paie): JsonResponse
    {
        return response()->json([
            'statut_calcul' => $paie->statut_calcul,
            'resultat_calcul' => $paie->resultat_calcul,
            'version' => $paie->version,
        ]);
    }

    /**
     * Liste des navires avec le nombre de marins éligibles.
     */
    public function naviresEligibles(Paie $paie): JsonResponse
    {
        $navires = AffectationMarin::pourPeriode($paie->date_debut, $paie->date_fin)
            ->actif()
            ->with('navire')
            ->get()
            ->groupBy('navire_id')
            ->map(fn ($affectations, $navireId) => [
                'navire_id' => $navireId,
                'navire_nom' => $affectations->first()->navire->nom ?? 'N/A',
                'nb_marins' => $affectations->count(),
            ])
            ->values();

        return response()->json($navires);
    }

    public function valider(Paie $paie): JsonResponse
    {
        $this->authorize('valider', $paie);

        $paie->update([
            'statut' => 'valide',
            'date_validation' => now(),
        ]);

        return response()->json(['message' => 'Paie validée avec succès.', 'paie' => $paie]);
    }

    public function cloturer(Paie $paie): JsonResponse
    {
        $this->authorize('cloturer', $paie);

        $paie->update([
            'statut' => 'cloture',
            'date_cloture' => now(),
        ]);

        return response()->json(['message' => 'Paie clôturée avec succès.', 'paie' => $paie]);
    }
}
