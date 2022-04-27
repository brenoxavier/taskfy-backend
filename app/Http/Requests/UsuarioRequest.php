<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id_usuario = request()->route('id_usuario') ? ',' . request()->route('id_usuario') : '';

        $validacao = [
            'nome' => ['string', 'min:5', 'max:255', 'required'],
            'email' => ['email', 'min:5', 'max:255', 'unique:usuarios,email' . $id_usuario, 'required'],
            'senha' => ['min:8', 'max:255', 'required'],
            'id_clockify' => ['string', 'min:20', 'max:28', 'unique:usuarios,id_clockify' . $id_usuario, 'required'],
            'foto_perfil' => ['active_url', 'min:5', 'max:255', 'required'],
            'carga_horaria' => ['integer', 'min:4', 'max:8', 'required'],
            'sabado' => ['boolean', 'required'],
            'ativo' => ['boolean', 'required'],
            'admin' => ['boolean', 'required']
        ];

        if ($id_usuario)
        {
            $validacao['senha'] = ['min:8', 'max:255', 'nullable'];
        }

        return $validacao;
    }
}
