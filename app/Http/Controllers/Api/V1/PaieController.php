<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Paie;
use App\Models\AffectationMarin;
use App\Services\CalculateurDePaie;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PaieController extends Controller
{
    protected $calculator;

    public function __construct(CalculateurDePaie $calculator)
    {
        $this->calculator = $calculator;
    }

    public function index(): JsonResponse
    {
        $paies = Paie::withCount('bulletins')->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($paies);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'num_paie' => 'required|string|max:20|unique:paies',
            'libelle' => 'required|string|max:100',
            'periode' => 'required|string|max:20',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        $paie = Paie::create($validated + ['statut' => 'brouillon']);
        return response()->json($paie, 201);
    }

    public function show(Paie $paie): JsonResponse
    {
        $paie->load(['bulletins.employe', 'bulletins.navire']);
        return response()->json($paie);
    }

    /**
     * Affectations actives éligibles pour la période de paie.
     */
    public function eligibles(Paie $paie): JsonResponse
    {
        $affectations = AffectationMarin::pourPeriode($paie->date_debut, $paie->date_fin)
                                        ->actif()
                                        ->with(['employe', 'navire', 'fonction', 'contratArmateur.devise'])
                                        ->get();

        return response()->json($affectations);
    }

    public function update(Request $request, Paie $paie): JsonResponse
    {
        // Ne pas autoriser la modification d'une paie déjà calculée ou validée
        if (!in_array($paie->statut, ['brouillon', 'calcule'])) {
            return response()->json(['error' => 'Cette paie ne peut plus être modifiée.'], 422);
        }

        $validated = $request->validate([
            'num_paie' => ['required', 'string', 'max:20', Rule::unique('paies')->ignore($paie->id)],
            'libelle' => 'required|string|max:100',
            'periode' => 'required|string|max:20',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        $paie->update($validated);
        return response()->json($paie);
    }

    public function destroy(Paie $paie): JsonResponse
    {
        if ($paie->statut !== 'brouillon') {
            return response()->json(['error' => 'Impossible de supprimer une paie déjà traitée.'], 422);
        }
        $paie->delete();
        return response()->json(null, 204);
    }

    /**
     * Action personnalisée : calculer les bulletins pour cette période de paie.
     */
    public function calculer(Paie $paie, Request $request): JsonResponse
    {
        if ($paie->statut !== 'brouillon') {
            return response()->json(['error' => 'Cette paie a déjà été calculée ou validée.'], 422);
        }

        // Récupérer toutes les affectations actives qui couvrent la période
        $affectations = AffectationMarin::pourPeriode($paie->date_debut, $paie->date_fin)
                                        ->with(['employe', 'navire', 'fonction'])
                                        ->get();

        if ($affectations->isEmpty()) {
            return response()->json(['error' => 'Aucune affectation active pour cette période.'], 422);
        }

        DB::beginTransaction();
        try {
            // Supprimer les anciens bulletins éventuels (recalcul)
            $paie->bulletins()->delete();

            foreach ($affectations as $affectation) {
                $bulletin = $this->calculator->calculateBulletin($paie, $affectation);
                // Le bulletin est automatiquement en statut 'calcule'
            }

            $paie->update(['statut' => 'calcule']);
            DB::commit();

            return response()->json([
                'message' => 'Calcul terminé avec succès.',
                'bulletins' => $paie->bulletins()->with('employe')->get()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur lors du calcul : ' . $e->getMessage()], 500);
        }
    }

    /**
     * Action personnalisée : valider la paie (passe en statut 'valide').
     */
    public function valider(Paie $paie): JsonResponse
    {
        if ($paie->statut !== 'calcule') {
            return response()->json(['error' => 'La paie doit être calculée avant validation.'], 422);
        }

        $paie->update([
            'statut' => 'valide',
            'date_validation' => now(),
        ]);

        return response()->json(['message' => 'Paie validée avec succès.', 'paie' => $paie]);
    }

    /**
     * Action personnalisée : clôturer la paie (passe en 'cloture').
     */
    public function cloturer(Paie $paie): JsonResponse
    {
        if ($paie->statut !== 'valide') {
            return response()->json(['error' => 'La paie doit être validée avant clôture.'], 422);
        }

        $paie->update([
            'statut' => 'cloture',
            'date_cloture' => now(),
        ]);

        return response()->json(['message' => 'Paie clôturée avec succès.', 'paie' => $paie]);
    }
}
