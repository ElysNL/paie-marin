<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navire extends Model
{
    use HasFactory;

    protected $fillable = [
        'armateur_id', 'compagnie_id', 'code', 'nom', 'immatriculation',
        'pavillon_id', 'type', 'actif'
    ];

    protected $casts = ['actif' => 'boolean'];

    public function armateur()
    {
        return $this->belongsTo(Armateur::class);
    }

    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }

    public function pavillon()
    {
        return $this->belongsTo(Pays::class, 'pavillon_id');
    }

    public function affectations()
    {
        return $this->hasMany(AffectationMarin::class);
    }

    public function bulletins()
    {
        return $this->hasMany(BulletinPaie::class);
    }
}
