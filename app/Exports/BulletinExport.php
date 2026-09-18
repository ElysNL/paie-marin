<?php

namespace App\Exports;

use App\Models\BulletinPaie;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BulletinExport implements FromArray, WithHeadings, WithStyles
{
    protected $bulletin;

    public function __construct(BulletinPaie $bulletin)
    {
        $this->bulletin = $bulletin;
    }

    public function headings(): array
    {
        return ['Désignation', 'Montant'];
    }

    public function array(): array
    {
        $b = $this->bulletin;
        $devise = $b->deviseSource->code ?? '';
        $rows = [];

        $rows[] = ['BULLETIN DE PAIE MARIN', ''];
        $rows[] = ['Société', $b->affectation->contratArmateur->armateur->raison_sociale ?? ''];
        $rows[] = ['', ''];
        $rows[] = ['NOM ET PRENOMS', $b->employe->prenom . ' ' . $b->employe->nom];
        $rows[] = ['N° LPM', $b->employe->num_lpm ?? '-'];
        $rows[] = ['N° CNAPS', $b->employe->num_cnaps ?? '-'];
        $rows[] = ['N° VISA CONTRAT', $b->employe->visa_contrat ?? '-'];
        $rows[] = ['NAVIRE', $b->navire->nom ?? ''];
        $rows[] = ['FONCTION', $b->affectation->fonction->libelle ?? ''];
        $rows[] = ['DATE DÉBUT/FIN', ($b->paie->date_debut?->format('d/m/Y') ?? '') . ' — ' . ($b->paie->date_fin?->format('d/m/Y') ?? '')];
        $rows[] = ['NOMBRE DE JOURS', $b->total_jours];
        $rows[] = ['', ''];

        $rows[] = ['VALEUR D\'INDICE', $b->affectation->taux_journalier ?? '-'];
        $rows[] = ['INDICE MONÉTAIRE', $devise];
        $rows[] = ['PAIEMENT PAR JOUR', number_format((float) $b->affectation->taux_journalier, 2, ',', ' ') . ' ' . $devise];
        $rows[] = ['PAIEMENT PAR MOIS', number_format((float) $b->affectation->taux_journalier * 30, 2, ',', ' ') . ' ' . $devise];
        $rows[] = ['', ''];

        $rows[] = ['GAINS', 'MONTANT'];
        foreach ($b->elements->where('elemPaie.type', 'GAIN') as $gain) {
            $rows[] = [$gain->elemPaie->libelle ?? '', number_format((float) $gain->montant, 2, ',', ' ') . ' ' . $devise];
        }
        $rows[] = ['TOTAL BRUT MARIN', number_format((float) $b->total_brut, 2, ',', ' ') . ' ' . $devise];
        $rows[] = ['', ''];

        $rows[] = ['RETENUES', 'MONTANT'];
        foreach ($b->elements->where('elemPaie.type', 'RETENUE') as $retenue) {
            $rows[] = [$retenue->elemPaie->libelle ?? '', number_format((float) $retenue->montant, 2, ',', ' ') . ' ' . $devise];
        }
        foreach ($b->cotisations as $cotisation) {
            $rows[] = [$cotisation->cotisation->libelle ?? '', number_format((float) $cotisation->montant_salarial, 2, ',', ' ') . ' ' . $devise];
        }
        $rows[] = ['TOTAL RETENUES', number_format((float) $b->total_retenues, 2, ',', ' ') . ' ' . $devise];
        $rows[] = ['', ''];

        $rows[] = ['NET À PAYER EN ARIARY', number_format((float) $b->net_a_payer, 2, ',', ' ') . ' MGA'];

        if ($b->deviseSource->code !== 'MGA' && $b->taux_change) {
            $rows[] = ['', ''];
            $rows[] = ['TAUX DE CHANGE', ''];
            $rows[] = ['1 ' . $devise, number_format((float) $b->taux_change, 2, ',', ' ') . ' Ar'];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}
