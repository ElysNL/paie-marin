<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TauxChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'devise_source_id', 'devise_cible_id', 'taux', 'date_taux', 'source'
    ];

    protected $casts = [
        'taux' => 'decimal:6',
        'date_taux' => 'date',
    ];

    public function deviseSource()
    {
        return $this->belongsTo(Devise::class, 'devise_source_id');
    }

    public function deviseCible()
    {
        return $this->belongsTo(Devise::class, 'devise_cible_id');
    }
}
