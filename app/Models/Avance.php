<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avance extends Model
{
    use HasFactory;

    protected $fillable = ['employer_id', 'date_avance', 'montant', 'devise_id', 'motif', 'statut', 'solde'];

    protected $casts = [
        'date_avance' => 'date',
        'montant' => 'decimal:2',
        'solde' => 'decimal:2',
    ];

    public function employer()
    {
        return $this->belongsTo(Employe::class);
    }

    public function devise()
    {
        return $this->belongsTo(Devise::class);
    }

    public function remboursements()
    {
        return $this->hasMany(RemboursementAvance::class);
    }
}
