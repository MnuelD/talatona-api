<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnexoNoticia extends Model
{
    //
    protected $table = 'anexo_noticias';

    protected $fillable = [
        'noticia_id',
        'anexo',
        'descricao', // Descrição opcional do anexo
        'meta_keywords', // Meta keywords opcionais
    ];

    // Um anexo pertence a uma notícia
    public function noticia()
    {
        return $this->belongsTo(Noticia::class, 'noticia_id');
    }
}

