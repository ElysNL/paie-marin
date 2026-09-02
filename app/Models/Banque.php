<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banque extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'pays_id', 'actif'];

    protected $casts = ['actif' => 'boolean'];

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }

    public function agences()
    {
        return $this->hasMany(Agence::class);
    }

    public function employes()
    {
        return $this->hasMany(Employe::class);
    }
}
