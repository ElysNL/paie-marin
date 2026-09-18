<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BulletinPaieResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'paie_id' => $this->paie_id,
            'employe_id' => $this->employe_id,
            'affectation_id' => $this->affectation_id,
            'navire_id' => $this->navire_id,
            'devise_source_id' => $this->devise_source_id,
            'devise_paiement_id' => $this->devise_paiement_id,
            'taux_change' => $this->taux_change,
            'date_taux_change' => $this->date_taux_change,
            'source_taux_change' => $this->source_taux_change,
            'total_jours' => $this->total_jours,
            'total_gains' => $this->total_gains,
            'total_brut' => $this->total_brut,
            'total_cotisations_salariales' => $this->total_cotisations_salariales,
            'total_cotisations_patronales' => $this->total_cotisations_patronales,
            'total_retenues' => $this->total_retenues,
            'net_a_payer' => $this->net_a_payer,
            'cout_total_employeur' => $this->cout_total_employeur,
            'statut' => $this->statut,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'employe' => new EmployeResource($this->whenLoaded('employe')),
            'navire' => new NavireResource($this->whenLoaded('navire')),
            'paie' => new PaieResource($this->whenLoaded('paie')),
            'affectation' => new AffectationResource($this->whenLoaded('affectation')),
            'deviseSource' => new DeviseResource($this->whenLoaded('deviseSource')),
            'devisePaiement' => new DeviseResource($this->whenLoaded('devisePaiement')),
            'jours' => BulletinJourResource::collection($this->whenLoaded('jours')),
            'elements' => BulletinElemPaieResource::collection($this->whenLoaded('elements')),
            'cotisations' => BulletinCotisationResource::collection($this->whenLoaded('cotisations')),
            'remboursementsAvances' => RemboursementAvanceResource::collection($this->whenLoaded('remboursementsAvances')),
            'delegations' => BulletinDelegationResource::collection($this->whenLoaded('delegations')),
        ];
    }
}
