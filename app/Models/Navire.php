<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Navire extends Model
{
    use HasFactory;

    protected $fillable = [
        'armateur_id', 'compagnie_id', 'code', 'nom', 'immatriculation',
        'pavillon_id', 'type', 'actif'
    ];

    protected $casts = ['actif' => 'boolean'];

    public function armateur(): BelongsTo
    {
        return $this->belongsTo(Armateur::class);
    }

    public function compagnie(): BelongsTo
    {
        return $this->belongsTo(Compagnie::class);
    }

    public function pavillon(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'pavillon_id');
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(AffectationMarin::class);
    }

    public function bulletins(): HasMany
    {
        return $this->hasMany(BulletinPaie::class);
    }
}
