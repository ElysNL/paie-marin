<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AvanceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'employe_id' => $this->employe_id,
            'date_avance' => $this->date_avance,
            'montant' => $this->montant,
            'devise_id' => $this->devise_id,
            'motif' => $this->motif,
            'statut' => $this->statut,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'employe' => new EmployeResource($this->whenLoaded('employe')),
            'devise' => new DeviseResource($this->whenLoaded('devise')),
        ];
    }
}
