<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'id_clockify',
        'nome',
        'email',
        'senha',
        'foto_perfil',
        'carga_horaria',
        'banco_horas',
        'sabado',
        'ativo',
        'admin'
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    public function timeEntries()
    {
        return $this->hasMany(Entrada::class, 'id_usuario');
    }
}
