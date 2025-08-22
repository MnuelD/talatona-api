<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pagina extends Model
{
    //
    protected $table = 'paginas';

    protected $fillable = [
        'titulo',
        'slug',
        'descricao',
        'imagem',
        'ultima_visualizacao',
        'visualizacoes',
        'meta_keywords',
        'estado',
    ];

    public function botoes()
{
    return $this->hasMany(BtnPagina::class, 'pagina_id');
}

public function destaques()
{
    return $this->hasMany(\App\Models\Destaque::class, 'pagina');
}


}
