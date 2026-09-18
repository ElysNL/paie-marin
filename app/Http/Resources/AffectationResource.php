<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AffectationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'employe_id' => $this->employe_id,
            'navire_id' => $this->navire_id,
            'fonction_id' => $this->fonction_id,
            'contrat_armateur_id' => $this->contrat_armateur_id,
            'date_embt' => $this->date_embt,
            'date_debt' => $this->date_debt,
            'taux_journalier' => $this->taux_journalier,
            'devise_id' => $this->devise_id,
            'statut' => $this->statut,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'employe' => new EmployeResource($this->whenLoaded('employe')),
            'navire' => new NavireResource($this->whenLoaded('navire')),
            'fonction' => new FonctionResource($this->whenLoaded('fonction')),
            'contratArmateur' => new ContratArmateurResource($this->whenLoaded('contratArmateur')),
            'devise' => new DeviseResource($this->whenLoaded('devise')),
        ];
    }
}
