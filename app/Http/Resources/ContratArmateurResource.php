<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContratArmateurResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'armateur_id' => $this->armateur_id,
            'code' => $this->code,
            'libelle' => $this->libelle,
            'devise_id' => $this->devise_id,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'taux_base' => $this->taux_base,
            'conditions' => $this->conditions,
            'actif' => $this->actif,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'armateur' => new ArmateurResource($this->whenLoaded('armateur')),
            'devise' => new DeviseResource($this->whenLoaded('devise')),
        ];
    }
}
