<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulletinJour extends Model
{
    use HasFactory;

    protected $table = 'bulletins_jour';

    protected $fillable = ['bulletin_id', 'date', 'type_jour', 'nombre', 'taux', 'montant'];

    protected $casts = [
        'date' => 'date',
        'nombre' => 'decimal:2',
        'taux' => 'decimal:2',
        'montant' => 'decimal:2',
    ];

    public function bulletin(): BelongsTo
    {
        return $this->belongsTo(BulletinPaie::class, 'bulletin_id');
    }
}
