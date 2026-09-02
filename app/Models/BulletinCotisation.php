<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinCotisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'bulletin_id', 'cotisation_id', 'assiette',
        'taux_salarial', 'montant_salarial',
        'taux_patronal', 'montant_patronal'
    ];

    protected $casts = [
        'assiette' => 'decimal:2',
        'taux_salarial' => 'decimal:4',
        'montant_salarial' => 'decimal:2',
        'taux_patronal' => 'decimal:4',
        'montant_patronal' => 'decimal:2',
    ];

    public function bulletin()
    {
        return $this->belongsTo(BulletinPaie::class);
    }

    public function cotisation()
    {
        return $this->belongsTo(Cotisation::class);
    }
}
