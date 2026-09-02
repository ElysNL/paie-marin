<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'libelle', 'organisme',
        'taux_salarial', 'plafond_salarial',
        'taux_patronal', 'plafond_patronal',
        'date_debut', 'date_fin', 'actif'
    ];

    protected $casts = [
        'taux_salarial' => 'decimal:4',
        'plafond_salarial' => 'decimal:2',
        'taux_patronal' => 'decimal:4',
        'plafond_patronal' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'actif' => 'boolean',
    ];

    public function bulletinsCotisations()
    {
        return $this->hasMany(BulletinCotisation::class);
    }
}
