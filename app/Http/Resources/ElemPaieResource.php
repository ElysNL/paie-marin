<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ElemPaieResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'libelle' => $this->libelle,
            'type' => $this->type,
            'est_variable' => $this->est_variable,
            'imposable' => $this->imposable,
            'cotisable' => $this->cotisable,
            'ordre' => $this->ordre,
            'actif' => $this->actif,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
