<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compagnie extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'pays_id', 'actif'];

    protected $casts = ['actif' => 'boolean'];

    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class);
    }

    public function navires(): HasMany
    {
        return $this->hasMany(Navire::class);
    }
}
