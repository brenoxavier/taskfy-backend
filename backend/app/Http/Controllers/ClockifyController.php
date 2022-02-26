<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Support\Facades\Http;

class ClockifyController extends Controller
{
    public function exibirUsuarios()
    {
        $resposta = Http::withHeaders([
            'X-Api-Key' => config('clockify.api_key')
        ])->get(config('clockify.api_url') . '/workspaces/' . config('clockify.workspace_id') . '/users');

        if ($resposta->successful())
        {
            $usuarios_clockify = [];

            foreach ($resposta->json() as $usuario_clockify)
            {
                $usuario = Usuario::where('id_clockify', $usuario_clockify['id'])
                    ->first();

                if (!$usuario)
                {
                    array_push($usuarios_clockify, [
                        'id_clockify' => $usuario_clockify['id'],
                        'nome' => $usuario_clockify['name'],
                        'email' => $usuario_clockify['email'],
                        'foto_perfil' => $usuario_clockify['profilePicture']
                    ]);
                }
            }

            return $usuarios_clockify;
        }

        abort(500, 'Não foi possivel estabelecer a conexão com o servidor do Clockify.');
    }
}
