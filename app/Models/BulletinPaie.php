<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinPaie extends Model
{
    use HasFactory;

    protected $fillable = [
        'paie_id', 'employe_id', 'affectation_id', 'navire_id',
        'devise_source_id', 'devise_paiement_id',
        'taux_change', 'date_taux_change', 'source_taux_change',
        'total_jours', 'total_gains', 'total_brut',
        'total_cotisations_salariales', 'total_retenues',
        'total_cotisations_patronales', 'net_a_payer', 'cout_total_employeur',
        'statut'
    ];

    protected $casts = [
        'taux_change' => 'decimal:6',
        'date_taux_change' => 'date',
        'total_jours' => 'decimal:2',
        'total_gains' => 'decimal:2',
        'total_brut' => 'decimal:2',
        'total_cotisations_salariales' => 'decimal:2',
        'total_retenues' => 'decimal:2',
        'total_cotisations_patronales' => 'decimal:2',
        'net_a_payer' => 'decimal:2',
        'cout_total_employeur' => 'decimal:2',
    ];

    public function paie()
    {
        return $this->belongsTo(Paie::class);
    }

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }

    public function affectation()
    {
        return $this->belongsTo(AffectationMarin::class);
    }

    public function navire()
    {
        return $this->belongsTo(Navire::class);
    }

    public function deviseSource()
    {
        return $this->belongsTo(Devise::class, 'devise_source_id');
    }

    public function devisePaiement()
    {
        return $this->belongsTo(Devise::class, 'devise_paiement_id');
    }

    public function jours()
    {
        return $this->hasMany(BulletinJour::class);
    }

    public function elements()
    {
        return $this->hasMany(BulletinElemPaie::class);
    }

    public function cotisations()
    {
        return $this->hasMany(BulletinCotisation::class);
    }

    public function remboursementsAvances()
    {
        return $this->hasMany(RemboursementAvance::class);
    }

    public function delegations()
    {
        return $this->hasMany(BulletinDelegation::class);
    }
}
