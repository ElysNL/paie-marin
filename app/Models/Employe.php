<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule', 'nom', 'prenom', 'date_naissance', 'lieu_naissance',
        'nationalite_id', 'adresse', 'telephone', 'email', 'cin',
        'banque_id', 'compte_bancaire', 'date_embauche', 'actif'
    ];

    protected $casts = [
        'actif' => 'boolean',
        'date_naissance' => 'date',
        'date_embauche' => 'date',
    ];

    // Relations
    public function nationalite()
    {
        return $this->belongsTo(Pays::class, 'nationalite_id');
    }

    public function banque()
    {
        return $this->belongsTo(Banque::class);
    }

    public function affectations()
    {
        return $this->hasMany(AffectationMarin::class);
    }

    public function bulletins()
    {
        return $this->hasMany(BulletinPaie::class);
    }

    public function avances()
    {
        return $this->hasMany(Avance::class);
    }

    public function delegations()
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
