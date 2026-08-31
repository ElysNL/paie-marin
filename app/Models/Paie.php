<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paie extends Model
{
    use HasFactory;

    protected $fillable = [
        'num_paie', 'libelle', 'periode', 'date_debut', 'date_fin',
        'statut', 'date_validation', 'date_cloture'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_validation' => 'date',
        'date_cloture' => 'date',
    ];

    public function bulletins()
    {
        return $this->hasMany(BulletinPaie::class);
    }
}
