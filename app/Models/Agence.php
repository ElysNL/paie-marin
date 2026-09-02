<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agence extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'banque_id', 'adresse', 'telephone', 'actif'];

    protected $casts = ['actif' => 'boolean'];

    public function banque()
    {
        return $this->belongsTo(Banque::class);
    }
}
