<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElemPaie extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'libelle', 'est_variable', 'type', 'modalite',
        'affichee', 'imposable', 'cotisable', 'ordre', 'actif'
    ];

    protected $casts = [
        'est_variable' => 'boolean',
        'affichee' => 'boolean',
        'imposable' => 'boolean',
        'cotisable' => 'boolean',
        'actif' => 'boolean',
        'ordre' => 'integer',
    ];

    public function bulletinsElements(): HasMany
    {
        return $this->hasMany(BulletinElemPaie::class);
    }
}
