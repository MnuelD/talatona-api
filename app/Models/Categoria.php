<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //
    protected $table = 'categorias';

    protected $fillable = [
        'nome',
        'slug',
    ];
    public function noticias()
    {
        return $this->hasMany(Noticia::class, 'categoria_id');
    }
}
