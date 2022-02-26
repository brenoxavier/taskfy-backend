<?php

namespace App\Services;

use App\Models\Feriado;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class FeriadoService
{
    public static function sincronizarFeriados(Carbon $data)
    {
        $api_url = config('feriado.api_url');
        $api_key = config('feriado.api_key');
        $cidade_ibge = config('feriado.cidade_ibge');

        $resposta = Http::get("{$api_url}?json=true&ano={$data->year}&ibge={$cidade_ibge}&token={$api_key}");

        if ($resposta->successful())
        {
            foreach ($resposta->json() as $feriado)
            {
                if ($feriado['type'] !== 'Dia Convencional')
                {
                    $novo_feriado = [
                        'data' => Carbon::createFromFormat('d/m/Y', $feriado['date']),
                        'descricao' => $feriado['name']
                    ];

                    Feriado::firstOrCreate($novo_feriado);
                }
            }
        }
    }
}
