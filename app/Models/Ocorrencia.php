<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    //
    protected $table = 'ocorrencias';

    protected $fillable = [
        'codigo_ocorrencia',
        'user_id',
        'anonimo',
        'nome',
        'email',
        'telefone',
        'bairro_id',
        'tipoOcorrencia_id',
        'localizacao_especifica',
        'descricao'
    ];

    public function tipoOcorrencia()
    {
        return $this->belongsTo(TipoOcorrencia::class, 'tipoOcorrencia_id');
    }

    public function bairro()
    {
        return $this->belongsTo(Bairro::class, 'bairro_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'ocorrencia_id');
    }
}
