<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoOcorrencia extends Model
{
    protected $table = 'tipo_ocorrencias';

    protected $fillable = [
        'nome',
        'descricao',
    ];

    public function ocorrencias()
    {
        return $this->hasMany(Ocorrencia::class, 'tipoOcorrencia_id');
    }

}
