<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bairro extends Model
{
    //
     protected $table = 'bairros';

    protected $fillable = [
        'comuna_id',
        'nome',
        'slug',
        'ponto_referencia',
        'imagem',
        'descricao',

    ];

    public function comuna()
    {
        return $this->belongsTo(Comuna::class, 'comuna_id');
    }

    public function municipes()
    {
        return $this->hasMany(Municipe::class, 'bairro_id');
    }
}
