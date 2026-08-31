<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinJour extends Model
{
    use HasFactory;

    protected $fillable = ['bulletin_id', 'date', 'type_jour', 'nombre', 'taux', 'montant'];

    protected $casts = [
        'date' => 'date',
        'nombre' => 'decimal:2',
        'taux' => 'decimal:2',
        'montant' => 'decimal:2',
    ];

    public function bulletin()
    {
        return $this->belongsTo(BulletinPaie::class);
    }
}
