<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BulletinElemPaieResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'bulletin_id' => $this->bulletin_id,
            'elem_paie_id' => $this->elem_paie_id,
            'quantite' => $this->quantite,
            'unite' => $this->unite,
            'base' => $this->base,
            'taux' => $this->taux,
            'montant' => $this->montant,
            'devise_id' => $this->devise_id,
            'description' => $this->description,
            'ordre' => $this->ordre,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'elemPaie' => new ElemPaieResource($this->whenLoaded('elemPaie')),
            'devise' => new DeviseResource($this->whenLoaded('devise')),
        ];
    }
}
