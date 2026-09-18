<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Devise extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'libelle', 'symbole', 'nb_decimales', 'actif'];

    protected $casts = [
        'actif' => 'boolean',
        'nb_decimales' => 'integer',
    ];

    public function contratsArmateur(): HasMany
    {
        return $this->hasMany(ContratArmateur::class);
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(AffectationMarin::class);
    }

    public function tauxChangesSource(): HasMany
    {
        return $this->hasMany(TauxChange::class, 'devise_source_id');
    }

    public function tauxChangesCible(): HasMany
    {
        return $this->hasMany(TauxChange::class, 'devise_cible_id');
    }

    public function avances(): HasMany
    {
        return $this->hasMany(Avance::class);
    }

    public function delegations(): HasMany
    {
        return $this->hasMany(Delegation::class);
    }

    // Scope
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    // etc. pour d'autres relations (bulletins, etc.)
}
