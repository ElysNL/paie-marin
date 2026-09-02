<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Armateur extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'adresse', 'telephone', 'email', 'pays_id', 'actif'];

    protected $casts = ['actif' => 'boolean'];

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }

    public function navires()
    {
        return $this->hasMany(Navire::class);
    }

    public function contrats()
    {
        return $this->hasMany(ContratArmateur::class);
    }

    public function specificites()
    {
        return $this->hasMany(SpecificiteArmateur::class);
    }
}
