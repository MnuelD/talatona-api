<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    //
     protected $table = 'funcionarios';

    protected $fillable = [
        'descricao',
        'user_id',
        'direccao_id',
        'slug',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function direccao()
    {
        return $this->belongsTo(Direccao::class, 'direccao_id');
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'responsavel_id');
    }
}
