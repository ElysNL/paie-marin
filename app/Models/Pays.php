<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pays extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'nationalite', 'actif'];

    protected $casts = [
        'actif' => 'boolean',
    ];

    // Relations
    public function banques(): HasMany
    {
        return $this->hasMany(Banque::class);
    }

    public function compagnies(): HasMany
    {
        return $this->hasMany(Compagnie::class);
    }

    public function armateurs(): HasMany
    {
        return $this->hasMany(Armateur::class);
    }

    public function navires(): HasMany
    {
        return $this->hasMany(Navire::class, 'pavillon_id');
    }

    public function employes(): HasMany
    {
        return $this->hasMany(Employe::class, 'nationalite_id');
    }

    // Scope
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}
