<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AffectationMarin;
use App\Models\BulletinPaie;
use App\Models\Navire;
use App\Models\Paie;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Statistiques en lecture seule pour le tableau de bord.
     */
    public function index(): JsonResponse
    {
        $employesActifs = AffectationMarin::actif()
            ->distinct('employe_id')
            ->count('employe_id');

        $affectationsActives = AffectationMarin::actif()->count();

        $navires = Navire::count();

        $paiesParStatut = Paie::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut')
            ->toArray();

        $bulletins = [
            'total' => BulletinPaie::count(),
            'net_a_payer' => (float) BulletinPaie::sum('net_a_payer'),
            'cout_total_employeur' => (float) BulletinPaie::sum('cout_total_employeur'),
        ];

        $dernieresPaies = Paie::withCount('bulletins')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'data' => [
                'employes_actifs' => $employesActifs,
                'affectations_actives' => $affectationsActives,
                'navires' => $navires,
                'paies_par_statut' => $paiesParStatut,
                'bulletins' => $bulletins,
                'dernieres_paies' => $dernieresPaies,
            ],
        ]);
    }
}