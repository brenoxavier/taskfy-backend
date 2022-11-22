<?php

namespace App\Services;

use App\Models\Usuario;
use App\Utilitarios;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ClockifyService
{
    public static function sincronizarEntradasClockify(Carbon $dataInicio)
    {
        $workspace_id = config('clockify.workspace_id');
        $api_url = config('clockify.api_url');
        $users = Usuario::where('ativo', true)
            ->get();

        foreach ($users as $user) {
            $resposta = Http::withHeaders([
                'X-Api-Key' => config('clockify.api_key')
            ])->get("$api_url/workspaces/$workspace_id/user/{$user['id_clockify']}/time-entries", [
                'start' => $dataInicio->toISOString()
            ]);

            if ($resposta->successful()) {
                foreach ($resposta->json() as $entradaTempo) {
                    Utilitarios::insertOrUpdateTimeEntry($user, $entradaTempo);
                }
            }
        }
    }
}
