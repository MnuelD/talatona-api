<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccao extends Model
{
    //
    protected $table = 'direccaos';

    protected $fillable = [
        'nome',
        'descricao',
        'responsavel_id',
        'telefone',
        'email',
        'imagem',
        'slug'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class, 'direccao_id');
    }
    public function getImagemUrlAttribute()
    {
        return asset($this->imagem);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'direccao_id');
    }

}
