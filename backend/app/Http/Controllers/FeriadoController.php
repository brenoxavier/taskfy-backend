<?php

namespace App\Http\Controllers;

use App\Models\Feriado;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FeriadoController extends Controller
{
    public function listar()
    {
        return Feriado::orderByDesc('data')
            ->get();
    }

    public function cadastrar(Request $request)
    {
        $novo_feriado = $request->validate([
            'data' => ['after_or_equal:today', 'unique:feriados,data', 'required'],
            'descricao' => ['string', 'min:5', 'max:255', 'required']
        ]);

        return Feriado::create($novo_feriado);
    }

    public function editar(Request $request, $id_feriado)
    {
        $feriado = Feriado::find($id_feriado);

        if ($feriado)
        {
            $feriado->fill($request->validate([
                'data' => ['after_or_equal:today', 'unique:feriados,data,' . $id_feriado, 'required'],
                'descricao' => ['string', 'min:5', 'max:255', 'required']
            ]));

            $feriado->save();
            return $feriado;
        }

        abort(404);
    }

    public function deletar($id_feriado)
    {
        $feriado = Feriado::find($id_feriado);

        if ($feriado)
        {
            $feriado->delete();

            return response()->json([
                "message" => "Feriado deletado com sucesso!"
            ]);
        }

        abort(404);
    }
}
