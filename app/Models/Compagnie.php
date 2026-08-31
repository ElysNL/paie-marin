<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compagnie extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'pays_id', 'actif'];

    protected $casts = ['actif' => 'boolean'];

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }

    public function navires()
    {
        return $this->hasMany(Navire::class);
    }
}
