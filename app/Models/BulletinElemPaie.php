<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinElemPaie extends Model
{
    use HasFactory;

    protected $fillable = [
        'bulletin_id', 'elem_paie_id', 'quantite', 'unite',
        'base', 'taux', 'montant', 'devise_id', 'description', 'ordre'
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'base' => 'decimal:2',
        'taux' => 'decimal:2',
        'montant' => 'decimal:2',
        'ordre' => 'integer',
    ];

    public function bulletin()
    {
        return $this->belongsTo(BulletinPaie::class);
    }

    public function elemPaie()
    {
        return $this->belongsTo(ElemPaie::class);
    }

    public function devise()
    {
        return $this->belongsTo(Devise::class);
    }
}
