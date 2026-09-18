<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fonction extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'libelle', 'description', 'actif'];

    protected $casts = ['actif' => 'boolean'];

    public function affectations(): HasMany
    {
        return $this->hasMany(AffectationMarin::class);
    }

    // Scope
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}
