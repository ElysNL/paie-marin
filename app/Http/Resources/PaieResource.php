<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaieResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'num_paie' => $this->num_paie,
            'libelle' => $this->libelle,
            'periode' => $this->periode,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'statut' => $this->statut,
            'date_validation' => $this->date_validation,
            'date_cloture' => $this->date_cloture,
            'version' => $this->version,
            'statut_calcul' => $this->statut_calcul,
            'resultat_calcul' => $this->resultat_calcul,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'bulletins_count' => $this->whenCounted('bulletins'),
            'bulletins' => BulletinPaieResource::collection($this->whenLoaded('bulletins')),
        ];
    }
}
