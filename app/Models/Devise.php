<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devise extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'libelle', 'symbole', 'nb_decimales', 'actif'];

    protected $casts = [
        'actif' => 'boolean',
        'nb_decimales' => 'integer',
    ];

    public function contratsArmateur()
    {
        return $this->hasMany(ContratArmateur::class);
    }

    public function affectations()
    {
        return $this->hasMany(AffectationMarin::class);
    }

    public function tauxChangesSource()
    {
        return $this->hasMany(TauxChange::class, 'devise_source_id');
    }

    public function tauxChangesCible()
    {
        return $this->hasMany(TauxChange::class, 'devise_cible_id');
    }

    public function avances()
    {
        return $this->hasMany(Avance::class);
    }

    public function delegations()
    {
        return $this->hasMany(Delegation::class);
    }

    // etc. pour d'autres relations (bulletins, etc.)
}
