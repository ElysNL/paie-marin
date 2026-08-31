<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificiteArmateur extends Model
{
    use HasFactory;

    protected $fillable = ['armateur_id', 'cle', 'valeur'];

    public function armateur()
    {
        return $this->belongsTo(Armateur::class);
    }
}
