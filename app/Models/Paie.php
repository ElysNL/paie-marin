<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paie extends Model
{
    use HasFactory;

    protected $fillable = [
        'num_paie', 'libelle', 'periode', 'date_debut', 'date_fin',
        'statut', 'date_validation', 'date_cloture',
        'version', 'statut_calcul', 'resultat_calcul',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_validation' => 'date',
        'date_cloture' => 'date',
        'version' => 'integer',
        'resultat_calcul' => 'array',
    ];

    public function bulletins(): HasMany
    {
        return $this->hasMany(BulletinPaie::class);
    }
}
