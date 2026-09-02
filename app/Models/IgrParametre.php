<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IgrParametre extends Model
{
    use HasFactory;

    protected $table = 'igr_parametres';

    protected $fillable = [
        'libelle', 'tranche_inf', 'tranche_sup', 'taux_igr',
        'date_debut', 'date_fin'
    ];

    protected $casts = [
        'tranche_inf' => 'decimal:2',
        'tranche_sup' => 'decimal:2',
        'taux_igr' => 'decimal:4',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function scopeValideLe($query, $date)
    {
        return $query->where('date_debut', '<=', $date)
                     ->where(function ($q) use ($date) {
                         $q->whereNull('date_fin')->orWhere('date_fin', '>=', $date);
                     });
    }
}
