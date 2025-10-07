<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipe extends Model
{
    //
    protected $table = 'municipes';
    protected $fillable = [
        'user_id',
        'bairro_id',
        'funcao',
    ];
    public function bairro()
    {
        return $this->belongsTo(Bairro::class, 'bairro_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

