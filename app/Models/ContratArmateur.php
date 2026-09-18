<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContratArmateur extends Model
{
    use HasFactory;

    protected $fillable = [
        'armateur_id', 'code', 'libelle', 'devise_id', 'date_debut',
        'date_fin', 'taux_base', 'conditions', 'actif'
    ];

    protected $casts = [
        'actif' => 'boolean',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'taux_base' => 'decimal:2',
    ];

    public function armateur(): BelongsTo
    {
        return $this->belongsTo(Armateur::class);
    }

    public function devise(): BelongsTo
    {
        return $this->belongsTo(Devise::class);
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(AffectationMarin::class);
    }
}
