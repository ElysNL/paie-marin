<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RemboursementAvance extends Model
{
    use HasFactory;

    protected $table = 'remboursements_avance';

    protected $fillable = ['avance_id', 'bulletin_id', 'montant'];

    protected $casts = ['montant' => 'decimal:2'];

    public function avance(): BelongsTo
    {
        return $this->belongsTo(Avance::class);
    }

    public function bulletin(): BelongsTo
    {
        return $this->belongsTo(BulletinPaie::class, 'bulletin_id');
    }
}
