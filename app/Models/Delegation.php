<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id', 'beneficiaire', 'montant', 'devise_id',
        'date_debut', 'date_fin', 'frequence', 'statut'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function employer()
    {
        return $this->belongsTo(Employe::class);
    }

    public function devise()
    {
        return $this->belongsTo(Devise::class);
    }

    public function bulletinsDelegations()
    {
        return $this->hasMany(BulletinDelegation::class);
    }
}
