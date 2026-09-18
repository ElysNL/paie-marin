<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Avance extends Model
{
    use HasFactory;

    protected $fillable = ['employe_id', 'date_avance', 'montant', 'devise_id', 'motif', 'statut', 'solde'];

    protected $casts = [
        'date_avance' => 'date',
        'montant' => 'decimal:2',
        'solde' => 'decimal:2',
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    public function devise(): BelongsTo
    {
        return $this->belongsTo(Devise::class);
    }

    public function remboursements(): HasMany
    {
        return $this->hasMany(RemboursementAvance::class);
    }
}
