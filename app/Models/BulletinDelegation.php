<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinDelegation extends Model
{
    use HasFactory;

    protected $fillable = ['bulletin_id', 'delegation_id', 'montant'];

    protected $casts = ['montant' => 'decimal:2'];

    public function bulletin()
    {
        return $this->belongsTo(BulletinPaie::class);
    }

    public function delegation()
    {
        return $this->belongsTo(Delegation::class);
    }
}
