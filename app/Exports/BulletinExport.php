<?php

namespace App\Exports;

use App\Models\BulletinPaie;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BulletinExport implements FromArray, WithHeadings
{
    protected $bulletin;

    public function __construct(BulletinPaie $bulletin)
    {
        $this->bulletin = $bulletin;
    }

    public function headings(): array
    {
        return [
            'Libellé',
            'Montant',
        ];
    }

    public function array(): array
    {
        $rows = [];

        $rows[] = ['Employé', $this->bulletin->employe?->nom . ' ' . $this->bulletin->employe?->prenom];
        $rows[] = ['Navire', $this->bulletin->navire?->nom ?? ''];

        foreach ($this->bulletin->elements as $element) {
            $rows[] = [
                $element->elemPaie?->libelle ?? '',
                $element->montant,
            ];
        }

        $rows[] = ['Total gains (BRUT)', $this->bulletin->total_brut];
        $rows[] = ['Total retenues', $this->bulletin->total_retenues];
        $rows[] = ['NET à payer', $this->bulletin->net_a_payer];

        return $rows;
    }
}
