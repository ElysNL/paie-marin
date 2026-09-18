<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DelegationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'employe_id' => $this->employe_id,
            'beneficiaire' => $this->beneficiaire,
            'montant' => $this->montant,
            'devise_id' => $this->devise_id,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'frequence' => $this->frequence,
            'statut' => $this->statut,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'employe' => new EmployeResource($this->whenLoaded('employe')),
            'devise' => new DeviseResource($this->whenLoaded('devise')),
        ];
    }
}
