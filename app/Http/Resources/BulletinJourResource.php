<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BulletinJourResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'bulletin_id' => $this->bulletin_id,
            'date' => $this->date,
            'type_jour' => $this->type_jour,
            'nombre' => $this->nombre,
            'taux' => $this->taux,
            'montant' => $this->montant,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
