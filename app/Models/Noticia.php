<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    protected $table = 'noticias';

    protected $fillable = [
        'titulo',
        'descricao',
        'categoria_id', // Certifique-se de que a coluna categoria_id existe na tabela noticias
        'link',
        'imagem',
        'status',
        'fonte',
        'slug',
        'meta_titulo',
        'meta_descricao',
        'meta_keywords',
        'meta_imagem',
    ];


    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function anexos()
{
    return $this->hasMany(AnexoNoticia::class, 'noticia_id');
}

}
