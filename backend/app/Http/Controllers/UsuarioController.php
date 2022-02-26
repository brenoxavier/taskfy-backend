<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function exibir($id_usuario = null)
    {
        if ($id_usuario)
        {
            $usuario = Usuario::find($id_usuario);

            if ($usuario) {
                return $usuario;
            }

            abort(404, "Usuario não encontrado");
        }

        return Usuario::where('admin', true)
            ->where('ativo', true)
            ->orWhere('ativo', true)
            ->orWhere('ativo', false)
            ->paginate();
    }

    public function salvar(UsuarioRequest $request)
    {
        $novo_usuario = $request->only([
            'nome',
            'email',
            'senha',
            'id_clockify',
            'foto_perfil',
            'carga_horaria',
            'sabado',
            'ativo',
            'admin'
        ]);

        $novo_usuario['senha'] = Hash::make($novo_usuario['senha']);
        Usuario::create($novo_usuario);

        return response()->json([
            'message' => 'Usuario cadastrado com sucesso!'
        ]);
    }

    public function editar(UsuarioRequest $request, $id_usuario)
    {
        $usuario_autenticado = auth()->user();
        $usuario = Usuario::find($id_usuario);

        if ($usuario) {
            if ($usuario_autenticado['admin'])
            {
                $usuario_editado = $request->only([
                    'nome',
                    'email',
                    'senha',
                    'id_clockify',
                    'foto_perfil',
                    'carga_horaria',
                    'sabado',
                    'remoto',
                    'ativo',
                    'admin'
                ]);

                $usuario_editado['senha'] = $usuario_editado['senha'] ? Hash::make($usuario_editado['senha']) : $usuario['senha'];
                $usuario->fill($usuario_editado);
                $usuario->save();

                return response()->json([
                    'message' => 'Usuario editado com sucesso!'
                ]);
            }

            if ($usuario_autenticado['id'] == $id_usuario)
            {
                $usuario_editado = $request->only([
                    'nome',
                    'email',
                    'senha',
                    'foto_perfil'
                ]);

                $usuario_editado['senha'] = $usuario_editado['senha'] ? Hash::make($usuario_editado['senha']) : $usuario['senha'];
                $usuario->fill($usuario_editado);
                $usuario->save();

                return response()->json([
                    'message' => 'Usuario editado com sucesso!'
                ]);
            }

            abort(401);
        }

        abort(404);
    }

    public function excluir($id_usuario)
    {
        $usuario = Usuario::find($id_usuario);

        if ($usuario)
        {
            $usuario->delete();

            return response()->json([
                "message" => "Usuario deletado com sucesso!"
            ]);
        }

        abort(404);
    }
}
