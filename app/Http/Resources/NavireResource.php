<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NavireResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'armateur_id' => $this->armateur_id,
            'compagnie_id' => $this->compagnie_id,
            'code' => $this->code,
            'nom' => $this->nom,
            'immatriculation' => $this->immatriculation,
            'pavillon_id' => $this->pavillon_id,
            'type' => $this->type,
            'actif' => $this->actif,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'armateur' => new ArmateurResource($this->whenLoaded('armateur')),
            'compagnie' => new CompagnieResource($this->whenLoaded('compagnie')),
            'pavillon' => new PaysResource($this->whenLoaded('pavillon')),
        ];
    }
}
