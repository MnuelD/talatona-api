<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instituicao extends Model
{
    //
        protected $table = 'instituicaos';
    protected $fillable = [
        'nome',
        'slug',
        'ponto_referencia',
        'imagem',
        'descricao',
        'telefone',
        'responsavel',
        'localizacao',
        'categoria',
        'tipo_instituicao',
    ];
    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel');
    }
    public function localizacao()
    {
        return $this->belongsTo(Bairro::class, 'localizacao');
    }

}

