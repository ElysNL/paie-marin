<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RemboursementAvanceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'avance_id' => $this->avance_id,
            'bulletin_id' => $this->bulletin_id,
            'montant' => $this->montant,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'avance' => new AvanceResource($this->whenLoaded('avance')),
        ];
    }
}
