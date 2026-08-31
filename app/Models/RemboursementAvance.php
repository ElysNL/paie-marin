<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemboursementAvance extends Model
{
    use HasFactory;

    protected $fillable = ['avance_id', 'bulletin_id', 'montant'];

    protected $casts = ['montant' => 'decimal:2'];

    public function avance()
    {
        return $this->belongsTo(Avance::class);
    }

    public function bulletin()
    {
        return $this->belongsTo(BulletinPaie::class);
    }
}
