<?php

namespace App\Http\Controllers;

use App\Models\TokenTemporario;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function autenticar(Request $request)
    {
        $usuario_informado = $request->validate([
            'email' => ['email', 'min:5', 'max:255', 'required'],
            'senha' => ['min:8', 'max:255', 'required']
        ]);

        $usuario = Usuario::where('email', $usuario_informado['email'])
            ->first();

        if ($usuario) {
            if (Hash::check($usuario_informado['senha'], $usuario['senha'])) {
                $usuario->tokens()->delete();
                $token = $usuario->createToken('Auth');

                return [
                    'usuario' => [
                        'nome' => $usuario['nome'],
                        'email' => $usuario['email'],
                        'foto_perfil' => $usuario['foto_perfil'],
                        'admin' => $usuario['admin']
                    ],
                    'token' => $token->plainTextToken
                ];
            }
        }

        abort(404, 'Usuario não encontrado.');
    }

    public function gerarTokenTemporario()
    {
        $usuario = auth()->user();

        return TokenTemporario::updateOrCreate([
            'usuario_id' => $usuario['id'],
            'token' => str_replace(['/', '.'], '', Hash::make($usuario['email'] . $usuario['created_at']))
        ]);
    }
}
