<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnexoOcorrencia extends Model
{
    //
    //
    protected $table = 'anexo_ocorrencias';

    protected $fillable = [
        'ocorrencia_id',
        'anexo',
        'descricao', // Descrição opcional do anexo
        'meta_keywords', // Meta keywords opcionais
    ];

    // Um anexo pertence a uma notícia
    public function ocorrencia()
    {
        return $this->belongsTo(Noticia::class, 'ocorrencia_id');
    }
}





