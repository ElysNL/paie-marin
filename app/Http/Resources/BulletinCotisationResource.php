<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BulletinCotisationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'bulletin_id' => $this->bulletin_id,
            'cotisation_id' => $this->cotisation_id,
            'assiette' => $this->assiette,
            'taux_salarial' => $this->taux_salarial,
            'montant_salarial' => $this->montant_salarial,
            'taux_patronal' => $this->taux_patronal,
            'montant_patronal' => $this->montant_patronal,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'cotisation' => new CotisationResource($this->whenLoaded('cotisation')),
        ];
    }
}
