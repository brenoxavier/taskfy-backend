<?php

namespace App;

use App\Models\Entrada;
use App\Models\Feriado;
use App\Models\Usuario;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Exception;

class Utilitarios
{
    public static function calcularDiasUteis(Usuario $usuario, Carbon $dia_mes, Carbon $fim_mes): array
    {
        $horas_uteis = 0;
        $dias = [];

        $feriados = Feriado::where('data', '>=', $dia_mes)
            ->where('data', '<=', $fim_mes)
            ->get();

        for ($i = 0; $i < $fim_mes->day; $i++) {
            $dia = [
                'data' => $dia_mes->toISOString(),
                'horas_uteis' => 0
            ];

            foreach ($feriados as $feriado) {
                if (Carbon::create($feriado['data'])->startOfDay()->equalTo($dia_mes)) {
                    $dia['feriado'] = $feriado['descricao'];
                    break;
                }
            }

            if (!isset($dia['feriado'])) {
                if (($dia_mes->dayName !== 'sábado' || $usuario['trabalha_sabado']) && $dia_mes->dayName !== 'domingo') {
                    $horas_uteis += $usuario['carga_horaria'];
                    $dia['horas_uteis'] = $usuario['carga_horaria'];
                }
            }

            $dias[$i] = $dia;
            $dia_mes->addDay();
        }

        return [
            'horas_uteis' => $horas_uteis,
            'dias' => $dias
        ];
    }

    /**
     * @throws Exception
     */
    public static function calcularHorasTrabalhadas(Usuario $usuario, Carbon $data_inicio, Carbon $data_fim = null): array
    {
        if (!$data_fim) {
            $entradas_de_tempo = $usuario->timeEntries()
                ->where('inicio', '>=', $data_inicio)
                ->whereNotNull('fim')
                ->orderByDesc('inicio')
                ->get();
        } else {
            $entradas_de_tempo = $usuario->timeEntries()
                ->where('inicio', '>=', $data_inicio)
                ->where('fim', '<=', $data_fim)
                ->orderByDesc('inicio')
                ->get();
        }

        $tempo = [
            'horas_diurnas' => [
                'horas' => 0,
                'minutos' => 0,
                'segundos' => 0
            ],
            'horas_noturnas' => [
                'horas' => 0,
                'minutos' => 0,
                'segundos' => 0
            ],
            'horas_totais' => [
                'horas' => 0,
                'minutos' => 0,
                'segundos' => 0
            ],
            'horas_justificadas' => [
                'horas' => 0,
                'minutos' => 0,
                'segundos' => 0
            ],
            'dias' => []
        ];

        foreach ($entradas_de_tempo as $entrada_de_tempo) {
            $entrada = [
                'id' => $entrada_de_tempo['id'],
                'inicio' => $entrada_de_tempo['inicio'],
                'fim' => $entrada_de_tempo['fim'],
                'motivo' => $entrada_de_tempo['motivo'],
                'justificada' => $entrada_de_tempo['justificada'],
                'horas_diurnas' => [
                    'horas' => 0,
                    'minutos' => 0,
                    'segundos' => 0
                ],
                'horas_noturnas' => [
                    'horas' => 0,
                    'minutos' => 0,
                    'segundos' => 0
                ],
                'horas_totais' => [
                    'horas' => 0,
                    'minutos' => 0,
                    'segundos' => 0
                ],
                'horas_justificadas' => [
                    'horas' => 0,
                    'minutos' => 0,
                    'segundos' => 0
                ],
            ];

            $inicio = Carbon::create($entrada['inicio']);
            $fim = Carbon::create($entrada['fim'] ?? Carbon::now());

            if (!isset($tempo['dias'][$inicio->day])) {
                $tempo['dias'][$inicio->day] = [
                    'horas_diurnas' => [
                        'horas' => 0,
                        'minutos' => 0,
                        'segundos' => 0
                    ],
                    'horas_noturnas' => [
                        'horas' => 0,
                        'minutos' => 0,
                        'segundos' => 0
                    ],
                    'horas_totais' => [
                        'horas' => 0,
                        'minutos' => 0,
                        'segundos' => 0
                    ],
                    'horas_justificadas' => [
                        'horas' => 0,
                        'minutos' => 0,
                        'segundos' => 0
                    ],
                    'entradas_de_tempo' => []
                ];
            }

            $horas_trabalhadas = $inicio->diff($fim);
            $horas_noturnas = Utilitarios::calcularHorasNoturnas($inicio, $fim);

            if ($entrada_de_tempo['justificada']) {
                $entrada['horas_justificadas']['horas'] = $horas_trabalhadas->h + ($horas_trabalhadas->d * 24);
                $entrada['horas_justificadas']['minutos'] = $horas_trabalhadas->i;
                $entrada['horas_justificadas']['segundos'] = $horas_trabalhadas->s;

                $tempo['dias'][$inicio->day]['horas_justificadas']['horas'] += $horas_trabalhadas->h + ($horas_trabalhadas->d * 24);
                $tempo['dias'][$inicio->day]['horas_justificadas']['minutos'] += $horas_trabalhadas->i;
                $tempo['dias'][$inicio->day]['horas_justificadas']['segundos'] += $horas_trabalhadas->s;

                $tempo['horas_justificadas']['horas'] += $horas_trabalhadas->h + ($horas_trabalhadas->d * 24);
                $tempo['horas_justificadas']['minutos'] += $horas_trabalhadas->i;
                $tempo['horas_justificadas']['segundos'] += $horas_trabalhadas->s;

                $entrada['horas_justificadas'] = Utilitarios::limitarHorario($entrada['horas_justificadas']);
                $tempo['dias'][$inicio->day]['horas_justificadas'] = Utilitarios::limitarHorario($tempo['dias'][$inicio->day]['horas_justificadas']);
            } else {
                $entrada['horas_totais']['horas'] = $horas_trabalhadas->h + ($horas_trabalhadas->d * 24);
                $entrada['horas_totais']['minutos'] = $horas_trabalhadas->i;
                $entrada['horas_totais']['segundos'] = $horas_trabalhadas->s;

                $tempo['dias'][$inicio->day]['horas_totais']['horas'] += $horas_trabalhadas->h + ($horas_trabalhadas->d * 24);
                $tempo['dias'][$inicio->day]['horas_totais']['minutos'] += $horas_trabalhadas->i;
                $tempo['dias'][$inicio->day]['horas_totais']['segundos'] += $horas_trabalhadas->s;

                $tempo['horas_totais']['horas'] += $horas_trabalhadas->h + ($horas_trabalhadas->d * 24);
                $tempo['horas_totais']['minutos'] += $horas_trabalhadas->i;
                $tempo['horas_totais']['segundos'] += $horas_trabalhadas->s;

                $horas_trabalhadas->h -= $horas_noturnas->h;
                $horas_trabalhadas->i -= $horas_noturnas->i;
                $horas_trabalhadas->s -= $horas_noturnas->s;

                $entrada['horas_diurnas']['horas'] = $horas_trabalhadas->h + ($horas_trabalhadas->d * 24);
                $entrada['horas_diurnas']['minutos'] = $horas_trabalhadas->i;
                $entrada['horas_diurnas']['segundos'] = $horas_trabalhadas->s;

                $tempo['dias'][$inicio->day]['horas_diurnas']['horas'] += $horas_trabalhadas->h + ($horas_trabalhadas->d * 24);
                $tempo['dias'][$inicio->day]['horas_diurnas']['minutos'] += $horas_trabalhadas->i;
                $tempo['dias'][$inicio->day]['horas_diurnas']['segundos'] += $horas_trabalhadas->s;

                $tempo['horas_diurnas']['horas'] += $horas_trabalhadas->h + ($horas_trabalhadas->d * 24);
                $tempo['horas_diurnas']['minutos'] += $horas_trabalhadas->i;
                $tempo['horas_diurnas']['segundos'] += $horas_trabalhadas->s;

                $entrada['horas_noturnas']['horas'] = $horas_noturnas->h + ($horas_noturnas->d * 24);
                $entrada['horas_noturnas']['minutos'] = $horas_noturnas->i;
                $entrada['horas_noturnas']['segundos'] = $horas_noturnas->s;

                $tempo['dias'][$inicio->day]['horas_noturnas']['horas'] += $horas_noturnas->h + ($horas_noturnas->d * 24);
                $tempo['dias'][$inicio->day]['horas_noturnas']['minutos'] += $horas_noturnas->i;
                $tempo['dias'][$inicio->day]['horas_noturnas']['segundos'] += $horas_noturnas->s;

                $tempo['horas_noturnas']['horas'] += $horas_noturnas->h + ($horas_noturnas->d * 24);
                $tempo['horas_noturnas']['minutos'] += $horas_noturnas->i;
                $tempo['horas_noturnas']['segundos'] += $horas_noturnas->s;

                $entrada['horas_totais'] = Utilitarios::limitarHorario($entrada['horas_totais']);
                $entrada['horas_diurnas'] = Utilitarios::limitarHorario($entrada['horas_diurnas']);
                $entrada['horas_noturnas'] = Utilitarios::limitarHorario($entrada['horas_noturnas']);

                $tempo['dias'][$inicio->day]['horas_totais'] = Utilitarios::limitarHorario($tempo['dias'][$inicio->day]['horas_totais']);
                $tempo['dias'][$inicio->day]['horas_diurnas'] = Utilitarios::limitarHorario($tempo['dias'][$inicio->day]['horas_diurnas']);
                $tempo['dias'][$inicio->day]['horas_noturnas'] = Utilitarios::limitarHorario($tempo['dias'][$inicio->day]['horas_noturnas']);
            }

            $tempo['dias'][$inicio->day]['entradas_de_tempo'][] = $entrada;
        }

        $tempo['horas_totais'] = Utilitarios::limitarHorario($tempo['horas_totais']);
        $tempo['horas_diurnas'] = Utilitarios::limitarHorario($tempo['horas_diurnas']);
        $tempo['horas_noturnas'] = Utilitarios::limitarHorario($tempo['horas_noturnas']);

        return $tempo;
    }

    /**
     * @throws Exception
     */
    public static function calcularHorasNoturnas(Carbon $inicio, Carbon $fim)
    {
        $inicio_horario_norturno = Carbon::create($inicio)
            ->setHour(22)
            ->setMinute(0)
            ->setSecond(0);

        $fim_horario_noturno = Carbon::create($inicio)
            ->setHour(5)
            ->setMinute(0)
            ->setSecond(0)
            ->addDay();

        if ($inicio->lte($inicio_horario_norturno) && $fim->gte($fim_horario_noturno)) {
            return CarbonInterval::create(0, 0, 0, 0, 7);
        } else if ($inicio->lt($inicio_horario_norturno) && $fim->between($inicio_horario_norturno, $fim_horario_noturno)) {
            return $inicio_horario_norturno->diff($fim);
        } else if ($inicio->between($inicio_horario_norturno, $fim_horario_noturno) && $fim->gt($fim_horario_noturno)) {
            return $inicio->diff($fim_horario_noturno);
        }

        return CarbonInterval::create(0);
    }

    public static function limitarHorario(array $horario): array
    {
        while ($horario['segundos'] >= 60) {
            $horario['segundos'] = $horario['segundos'] - 60;
            $horario['minutos']++;
        }

        while ($horario['minutos'] >= 60) {
            $horario['minutos'] = $horario['minutos'] - 60;
            $horario['horas']++;
        }

        return [
            'horas' => $horario['horas'],
            'minutos' => $horario['minutos'],
            'segundos' => $horario['segundos']
        ];
    }

    public static function getEnvironmentVariable(string $key, string $default = null)
    {
        return getenv($key) ? getenv($key) : env($key, $default);
    }

    public static function insertOrUpdateTimeEntry(Usuario $user, $time_entry)
    {
        $new_time_entry = [
            'id_usuario' => $user['id'],
            'id_entrada' => $time_entry['id'],
            'inicio' => Carbon::create($time_entry['timeInterval']['start'])->setTimezone('America/Sao_Paulo'),
            'fim' => $time_entry['timeInterval']['end'] ? Carbon::create($time_entry['timeInterval']['end'])->setTimezone('America/Sao_Paulo') : null
        ];

        Entrada::updateOrCreate([
            'id_entrada' => $time_entry['id']
        ], $new_time_entry);
    }
}
