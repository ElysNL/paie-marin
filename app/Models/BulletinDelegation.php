<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulletinDelegation extends Model
{
    use HasFactory;

    protected $table = 'bulletins_delegation';

    protected $fillable = ['bulletin_id', 'delegation_id', 'montant'];

    protected $casts = ['montant' => 'decimal:2'];

    public function bulletin(): BelongsTo
    {
        return $this->belongsTo(BulletinPaie::class, 'bulletin_id');
    }

    public function delegation(): BelongsTo
    {
        return $this->belongsTo(Delegation::class);
    }
}
