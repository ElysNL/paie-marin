<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'nationalite', 'actif'];

    protected $casts = [
        'actif' => 'boolean',
    ];

    // Relations
    public function banques()
    {
        return $this->hasMany(Banque::class);
    }

    public function compagnies()
    {
        return $this->hasMany(Compagnie::class);
    }

    public function armateurs()
    {
        return $this->hasMany(Armateur::class);
    }

    public function navires()
    {
        return $this->hasMany(Navire::class, 'pavillon_id');
    }

    public function employes()
    {
        return $this->hasMany(Employe::class, 'nationalite_id');
    }

    // Scope
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}
