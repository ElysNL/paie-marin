<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CotisationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'libelle' => $this->libelle,
            'organisme' => $this->organisme,
            'taux_salarial' => $this->taux_salarial,
            'plafond_salarial' => $this->plafond_salarial,
            'taux_patronal' => $this->taux_patronal,
            'plafond_patronal' => $this->plafond_patronal,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'actif' => $this->actif,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
