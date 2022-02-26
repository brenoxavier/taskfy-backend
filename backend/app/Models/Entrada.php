<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_usuario',
        'id_entrada',
        'inicio',
        'fim',
        'motivo'
    ];
}
