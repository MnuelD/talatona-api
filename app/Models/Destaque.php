<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destaque extends Model
{
    protected $table = 'destaques';

    protected $fillable = [
        'titulo',
        'descricao',
        'icone',
        'link_text',
        'link',
        'pagina'
    ];

    public function paginaObj()
{
    return $this->belongsTo(\App\Models\Pagina::class, 'pagina');
}

}
