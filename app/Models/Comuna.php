<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
    //
     protected $table = 'comunas';

    protected $fillable = [
        'nome',
        'descricao',

    ];

    public function bairros()
    {
        return $this->hasMany(Bairro::class, 'comuna_id');
    }
}
// --- IGNORE ---
