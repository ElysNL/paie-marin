<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'matricule' => $this->matricule,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'date_naissance' => $this->date_naissance,
            'lieu_naissance' => $this->lieu_naissance,
            'nationalite_id' => $this->nationalite_id,
            'adresse' => $this->adresse,
            'telephone' => $this->telephone,
            'email' => $this->email,
            'cin' => $this->cin,
            'banque_id' => $this->banque_id,
            'compte_bancaire' => $this->compte_bancaire,
            'date_embauche' => $this->date_embauche,
            'nbre_charges' => $this->nbre_charges,
            'actif' => $this->actif,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'nationalite' => new PaysResource($this->whenLoaded('nationalite')),
            'banque' => new BanqueResource($this->whenLoaded('banque')),
        ];
    }
}
