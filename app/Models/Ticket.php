<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    //
    protected $table = 'tickets';

    protected $fillable = [
        'ocorrencia_id',
        'direccao_id',
        'responsavel_id',
        'status',
        'observacoes'
    ];

    public function ocorrencia()
    {
        return $this->belongsTo(Ocorrencia::class, 'ocorrencia_id');
    }
    public function direccao()
    {
        return $this->belongsTo(Direccao::class, 'direccao_id');
    }
    public function responsavel()
    {
        return $this->belongsTo(Funcionario::class, 'responsavel_id');
    }
}
