<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fonction extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'libelle', 'description', 'actif'];

    protected $casts = ['actif' => 'boolean'];

    public function affectations()
    {
        return $this->hasMany(AffectationMarin::class);
    }
}
