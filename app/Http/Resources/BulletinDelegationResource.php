<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BulletinDelegationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'bulletin_id' => $this->bulletin_id,
            'delegation_id' => $this->delegation_id,
            'montant' => $this->montant,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'delegation' => new DelegationResource($this->whenLoaded('delegation')),
        ];
    }
}
