<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AffectationMarin extends Model
{
    use HasFactory;

    protected $table = 'affectations_marin';

    protected $fillable = [
        'employe_id', 'navire_id', 'fonction_id', 'contrat_armateur_id',
        'date_embt', 'date_debt', 'taux_journalier', 'devise_id', 'statut'
    ];

    protected $casts = [
        'date_embt' => 'date',
        'date_debt' => 'date',
        'taux_journalier' => 'decimal:2',
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    public function navire(): BelongsTo
    {
        return $this->belongsTo(Navire::class);
    }

    public function fonction(): BelongsTo
    {
        return $this->belongsTo(Fonction::class);
    }

    public function contratArmateur(): BelongsTo
    {
        return $this->belongsTo(ContratArmateur::class);
    }

    public function devise(): BelongsTo
    {
        return $this->belongsTo(Devise::class);
    }

    public function bulletins(): HasMany
    {
        return $this->hasMany(BulletinPaie::class);
    }

    // Scope
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopePourPeriode($query, $dateDebut, $dateFin)
    {
        return $query->where('date_embt', '<=', $dateFin)
                     ->where(function ($q) use ($dateDebut) {
                         $q->whereNull('date_debt')
                           ->orWhere('date_debt', '>=', $dateDebut);
                     });
    }
}
