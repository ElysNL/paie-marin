<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Banque extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'pays_id', 'actif'];

    protected $casts = ['actif' => 'boolean'];

    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class);
    }

    public function agences(): HasMany
    {
        return $this->hasMany(Agence::class);
    }

    public function employes(): HasMany
    {
        return $this->hasMany(Employe::class);
    }
}
