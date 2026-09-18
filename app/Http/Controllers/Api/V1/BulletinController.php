<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BulletinPaieResource;
use App\Models\BulletinPaie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BulletinExport;

class BulletinController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', BulletinPaie::class);
        $query = BulletinPaie::with(['employe', 'navire', 'paie']);

        if ($request->has('employe_id')) {
            $query->where('employe_id', $request->employe_id);
        }
        if ($request->has('paie_id')) {
            $query->where('paie_id', $request->paie_id);
        }

        $bulletins = $query->paginate(50);
        return BulletinPaieResource::collection($bulletins);
    }

    public function show(BulletinPaie $bulletin): JsonResponse
    {
        $bulletin->load([
            'employe.nationalite',
            'navire',
            'paie',
            'affectation.fonction',
            'affectation.contratArmateur',
            'deviseSource',
            'devisePaiement',
            'jours',
            'elements.elemPaie',
            'cotisations.cotisation',
            'remboursementsAvances.avance',
            'delegations.delegation'
        ]);

        return response()->json(new BulletinPaieResource($bulletin));
    }

    public function destroy(BulletinPaie $bulletin): JsonResponse
    {
        $this->authorize('delete', $bulletin);
        if (!in_array($bulletin->paie->statut, ['brouillon', 'calcule'])) {
            return response()->json(['message' => 'Impossible de supprimer un bulletin d\'une paie validée ou clôturée.'], 422);
        }
        $bulletin->delete();
        return response()->json(null, 204);
    }

    /**
     * Export PDF du bulletin
     */
    public function exportPdf(BulletinPaie $bulletin): \Illuminate\Http\Response
    {
        $this->authorize('export', $bulletin);

        $bulletin->load([
            'employe.nationalite',
            'navire',
            'paie',
            'affectation.fonction',
            'affectation.contratArmateur.armateur',
            'deviseSource',
            'devisePaiement',
            'jours',
            'elements.elemPaie',
            'cotisations.cotisation',
            'remboursementsAvances.avance',
            'delegations.delegation'
        ]);

        $tauxChanges = \App\Models\TauxChange::where('devise_source_id', $bulletin->devise_source_id)
            ->where('devise_cible_id', $bulletin->devise_paiement_id)
            ->orderBy('date_taux', 'desc')
            ->limit(5)
            ->get();

        $pdf = Pdf::loadView('pdf.bulletin-paie', ['bulletin' => $bulletin, 'tauxChanges' => $tauxChanges]);
        return $pdf->download("bulletin_{$bulletin->id}.pdf");
    }

    /**
     * Export Excel du bulletin
     */
    public function exportExcel(BulletinPaie $bulletin): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $this->authorize('export', $bulletin);

        $bulletin->load([
            'employe.nationalite',
            'navire',
            'paie',
            'affectation.fonction',
            'affectation.contratArmateur.armateur',
            'deviseSource',
            'devisePaiement',
            'jours',
            'elements.elemPaie',
            'cotisations.cotisation',
            'remboursementsAvances.avance',
            'delegations.delegation'
        ]);

        return Excel::download(new BulletinExport($bulletin), "bulletin_{$bulletin->id}.xlsx");
    }
}
