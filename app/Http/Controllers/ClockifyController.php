<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Utilitarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClockifyController extends Controller
{
    public function atualizar(Request $request)
    {
        $secret = $request->header('clockify-signature');

        if ($secret) {
            $event_type = $request->header('clockify-webhook-event-type');

            if ($this->validateSecretHeader($secret, $event_type)) {
                $request_body = $request->only(['id', 'userId', 'timeInterval']);

                $user = Usuario::where('ativo', true)
                    ->where('id_clockify', $request_body['userId'])
                    ->first();

                if ($user) {
                    Utilitarios::insertOrUpdateTimeEntry($user, $request_body);
                    return;
                }

                abort(400, "Usuario não encontrado");
            }
        }

        abort(401);
    }

    private function validateSecretHeader(string $secret, string $event_type): bool
    {
        $environment_secret = "";

        switch ($event_type) {
            case 'NEW_TIMER_STARTED':
                $environment_secret = env('CLOCKIFY_TIMER_STARDED_SECRET');
                break;

            case 'TIMER_STOPPED':
                $environment_secret = env('CLOCKIFY_TIMER_STOPPED_SECRET');
                break;

            case 'NEW_TIME_ENTRY':
                $environment_secret = env('CLOCKIFY_TIMER_ENTRY_CREATED_SECRET');
                break;

            case 'TIME_ENTRY_UPDATED':
                $environment_secret = env('CLOCKIFY_TIMER_ENTRY_UPDATED_SECRET');
                break;

            case 'TIME_ENTRY_DELETED':
                $environment_secret = env('CLOCKIFY_TIMER_ENTRY_DELETED_SECRET');
                break;
        }

        return $environment_secret == $secret;
    }

    public function exibirUsuarios()
    {
        $resposta = Http::withHeaders([
            'X-Api-Key' => config('clockify.api_key')
        ])->get(config('clockify.api_url') . '/workspaces/' . config('clockify.workspace_id') . '/users');

        if ($resposta->successful()) {
            $usuarios_clockify = [];

            foreach ($resposta->json() as $usuario_clockify) {
                $usuario = Usuario::where('id_clockify', $usuario_clockify['id'])
                    ->first();

                if (!$usuario) {
                    $usuarios_clockify[] = [
                        'id_clockify' => $usuario_clockify['id'],
                        'nome' => $usuario_clockify['name'],
                        'email' => $usuario_clockify['email'],
                        'foto_perfil' => $usuario_clockify['profilePicture']
                    ];
                }
            }

            return $usuarios_clockify;
        }

        abort(500, 'Não foi possivel estabelecer a conexão com o servidor do Clockify.');
    }
}
