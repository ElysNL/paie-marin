<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employe extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule', 'num_lpm', 'num_cnaps', 'visa_contrat',
        'nom', 'prenom', 'date_naissance', 'lieu_naissance',
        'nationalite_id', 'adresse', 'telephone', 'email', 'cin',
        'banque_id', 'compte_bancaire', 'date_embauche', 'nbre_charges', 'actif'
    ];

    protected $casts = [
        'actif' => 'boolean',
        'nbre_charges' => 'integer',
        'date_naissance' => 'date',
        'date_embauche' => 'date',
    ];

    // Relations
    public function nationalite(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'nationalite_id');
    }

    public function banque(): BelongsTo
    {
        return $this->belongsTo(Banque::class);
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(AffectationMarin::class);
    }

    public function bulletins(): HasMany
    {
        return $this->hasMany(BulletinPaie::class);
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

    public function getNomCompletAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }
}
