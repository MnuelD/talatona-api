<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BtnPagina extends Model
{
    //
     protected $table = 'btn_paginas';

    protected $fillable = [
        'texto',
        'link',
        'icone',
        'target',
        'pagina_id',

    ];

    public function pagina()
{
    return $this->belongsTo(Pagina::class, 'pagina_id');
}

}
