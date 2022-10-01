<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\TokenTemporario;
use App\Models\Usuario;
use App\Utilitarios;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EntradaTempoController extends Controller
{
    public function cadastrar(Request $request)
    {
        $nova_entrada = $request->validate([
            'id_usuario' => ['integer', 'min:1', 'required'],
            'inicio' => ['date', 'required'],
            'fim' => ['date', 'required'],
            'motivo' => ['string', 'min:5', 'max:255', 'required'],
            'justificada' => ['boolean', 'required']
        ]);

        // Valida o id enviado, se não achar, retorna um 404
        Usuario::findOrFail($nova_entrada['id_usuario']);
        return Entrada::create($nova_entrada);
    }

    public function editar(Request $request, $id_entrada)
    {
        $entrada_editada = $request->validate([
            'inicio' => ['date', 'required'],
            'fim' => ['date', 'required'],
            'motivo' => ['string', 'min:5', 'max:255', 'required'],
        ]);

        $entrada = Entrada::find($id_entrada);

        if ($entrada) {
            $entrada->fill($entrada_editada);
            $entrada->save();

            return $entrada;
        }

        abort(404);
    }

    public function deletar($id_entrada)
    {
        $entrada = Entrada::find($id_entrada);

        if ($entrada) {
            $entrada->delete();

            return response()->json([
                "message" => "Entrada deletada com sucesso!"
            ]);
        }

        abort(404);
    }

    /**
     * @throws Exception
     */
    public function relatorio(Request $request, $id_usuario = null)
    {
        $dados = $request->validate([
            'data' => ['date', 'nullable'],
            'pdf' => ['boolean', 'nullable'],
            'token' => ['string', 'min:0', 'max:255', 'nullable']
        ]);

        $usuario_autenticado = auth('sanctum')->user();

        if (!$usuario_autenticado) {
            if (isset($dados['token'])) {
                $usuario_autenticado = TokenTemporario::where('token', $dados['token'])
                    ->first()
                    ->usuario;
            }
        }

        if ($usuario_autenticado) {
            $usuario = ($id_usuario && $usuario_autenticado['admin']) ? Usuario::find($id_usuario) : $usuario_autenticado;

            if ($usuario) {
                if (isset($dados['data'])) {
                    $data_inicio = Carbon::create($dados['data'])
                        ->setTimezone('America/Sao_Paulo')
                        ->startOfDay()
                        ->startOfMonth();

                    $data_fim = Carbon::create($dados['data'])
                        ->setTimezone('America/Sao_Paulo')
                        ->startOfDay()
                        ->endOfMonth();
                } else {
                    $data_inicio = Carbon::today()->startOfMonth();
                    $data_fim = Carbon::today()->endOfMonth();
                }

                $horas_trabalhadas = Utilitarios::calcularHorasTrabalhadas($usuario, $data_inicio, $data_fim);
                $dias_uteis = Utilitarios::calcularDiasUteis($usuario, $data_inicio, $data_fim);

                $relatorio = [
                    'data' => $data_inicio,
                    'usuario' => $usuario,
                    'banco_horas' => [
                        'horas' => 0,
                        'minutos' => 0
                    ],
                    'horas_trabalhadas' => $horas_trabalhadas,
                    'saldo_mensal' => [
                        'horas' => 0,
                        'minutos' => 0
                    ],
                    'dias_do_mes' => $dias_uteis
                ];

                if ($usuario['banco_horas'] > 0) {
                    while ($usuario['banco_horas'] >= 60) {
                        $relatorio['banco_horas']['horas'] += 1;
                        $usuario['banco_horas'] -= 60;
                    }

                } else {
                    while ($usuario['banco_horas'] <= -60) {
                        $relatorio['banco_horas']['horas'] -= 1;
                        $usuario['banco_horas'] += 60;
                    }

                }

                $relatorio['banco_horas']['minutos'] = $usuario['banco_horas'];
                $relatorio['saldo_mensal']['horas_totais'] = $horas_trabalhadas['horas_totais']['horas'] - $dias_uteis['horas_uteis'];
                $relatorio['saldo_mensal']['minutos_totais'] = $horas_trabalhadas['horas_totais']['minutos'];

                $relatorio['saldo_mensal']['horas_totais'] += $horas_trabalhadas['horas_justificadas']['horas'];
                $relatorio['saldo_mensal']['minutos_totais'] += $horas_trabalhadas['horas_justificadas']['minutos'];

                if (isset($dados['pdf'])) {
                    if ($dados['pdf']) {
                        $pdf = app()->make('dompdf.wrapper');
                        $relatorio['usuario'] = $usuario;

                        return $pdf->loadView('relatorio', $relatorio)
                            ->setPaper('a4', 'landscape')
                            ->download('relatorio.pdf');
                    }
                }

                return $relatorio;
            }

            abort(404);
        }

        abort(401);
    }
}
