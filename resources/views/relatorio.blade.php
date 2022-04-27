<table style="width: 100vw; font-family: sans-serif;">
    <tr>
        <td>
            <h2>Relatório - {{ $data->format('F \d\e Y') }}</h2>
            <p>{{ $usuario['nome'] }}</p>
        </td>
        <td style="text-align: right;">
            <img src="http://localhost:8080/img/newm.png" alt="NewM" style="width: 10rem;">
        </td>
    </tr>
</table>

@foreach($horas_trabalhadas['dias'] as $dia_trabalhado)
    <h3 style="font-family: sans-serif;">{{ \Carbon\Carbon::create($dia_trabalhado['entradas_de_tempo'][0]['inicio'])->format('d/m/Y - l') }}</h3>

    <table style="width: 100vw; padding: 0.3rem; border-bottom: 0.1rem solid black; font-family: sans-serif; text-align: start;">
        <tr>
            <th style="text-align: start;">Entrada</th>
            <th style="text-align: start;">Saida</th>
            <th style="text-align: start;">Horas Diurnas</th>
            <th style="text-align: start;">Horas Noturnas</th>
            <th style="text-align: start;">Horas Totais</th>
        </tr>

        @foreach($dia_trabalhado['entradas_de_tempo'] as $entrada_de_tempo)
            <tr>
                <td>{{ \Carbon\Carbon::create($entrada_de_tempo['inicio'])->format('d/m/Y - H:i') }}</td>
                <td>{{ \Carbon\Carbon::create($entrada_de_tempo['fim'])->format('d/m/Y - H:i') }}</td>
                <td>{{ $entrada_de_tempo['horas_diurnas']['horas'] < 10 ? '0' . $entrada_de_tempo['horas_diurnas']['horas'] : $entrada_de_tempo['horas_diurnas']['horas'] }}:{{ $entrada_de_tempo['horas_diurnas']['minutos'] < 10 ? '0' . $entrada_de_tempo['horas_diurnas']['minutos'] : $entrada_de_tempo['horas_diurnas']['minutos'] }}</td>
                <td>{{ $entrada_de_tempo['horas_noturnas']['horas'] < 10 ? '0' . $entrada_de_tempo['horas_noturnas']['horas'] : $entrada_de_tempo['horas_noturnas']['horas'] }}:{{ $entrada_de_tempo['horas_noturnas']['minutos'] < 10 ? '0' . $entrada_de_tempo['horas_noturnas']['minutos'] : $entrada_de_tempo['horas_noturnas']['minutos'] }}</td>
                <td>{{ $entrada_de_tempo['horas_totais']['horas'] < 10 ? '0' . $entrada_de_tempo['horas_totais']['horas'] : $entrada_de_tempo['horas_totais']['horas'] }}:{{ $entrada_de_tempo['horas_totais']['minutos'] < 10 ? '0' . $entrada_de_tempo['horas_totais']['minutos'] : $entrada_de_tempo['horas_totais']['minutos'] }}</td>
            </tr>
        @endforeach
    </table>
@endforeach

<h3 style="font-family: sans-serif;">Total</h3>

<table style="width: 100vw; font-family: sans-serif; text-align: center;">
    <tr>
        <td>
            Horas Diurnas: <b>{{ $horas_trabalhadas['horas_diurnas']['horas'] < 10 ? '0' . $horas_trabalhadas['horas_diurnas']['horas'] : $horas_trabalhadas['horas_diurnas']['horas'] }}:{{ $horas_trabalhadas['horas_diurnas']['minutos'] < 10 ? '0' . $horas_trabalhadas['horas_diurnas']['minutos'] : $horas_trabalhadas['horas_diurnas']['minutos'] }}</b>
        </td>
        <td>
            Horas Noturnas: <b>{{ $horas_trabalhadas['horas_noturnas']['horas'] < 10 ? '0' . $horas_trabalhadas['horas_noturnas']['horas'] : $horas_trabalhadas['horas_noturnas']['horas'] }}:{{ $horas_trabalhadas['horas_noturnas']['minutos'] < 10 ? '0' . $horas_trabalhadas['horas_noturnas']['minutos'] : $horas_trabalhadas['horas_noturnas']['minutos'] }}</b>
        </td>
    </tr>
    <tr>
        <td>
            Horas Totais: <b>{{ $horas_trabalhadas['horas_totais']['horas'] < 10 ? '0' . $horas_trabalhadas['horas_totais']['horas'] : $horas_trabalhadas['horas_totais']['horas'] }}:{{ $horas_trabalhadas['horas_totais']['minutos'] < 10 ? '0' . $horas_trabalhadas['horas_totais']['minutos'] : $horas_trabalhadas['horas_totais']['minutos'] }}</b>
        </td>
        <td>
            Horas Úteis do Mês: <b>{{ $dias_do_mes['horas_uteis'] }}:00</b>
        </td>
    </tr>
    <tr>
        <td>
            Saldo do Mês: <b>{{ ($saldo_mensal['horas_totais'] < 0 || $saldo_mensal['minutos_totais'] < 0) ? '-' : '' }}{{ abs($saldo_mensal['horas_totais']) < 10 ? '0' . abs($saldo_mensal['horas_totais']) : abs($saldo_mensal['horas_totais']) }}:{{ abs($saldo_mensal['minutos_totais']) < 10 ? '0' . abs($saldo_mensal['minutos_totais']) : abs($saldo_mensal['minutos_totais']) }}</b>
        </td>
        <td>
            Banco de Horas: <b>{{ ($banco_horas['horas'] < 0 || $banco_horas['minutos'] < 0) ? '-' : '' }}{{ abs($banco_horas['horas']) < 10 ? '0' . abs($banco_horas['horas']) : abs($banco_horas['horas']) }}:{{ abs($banco_horas['minutos']) < 10 ? '0' . abs($banco_horas['minutos']) : abs($banco_horas['minutos']) }}</b>
        </td>
    </tr>
</table>

<table style="width: 100vw; font-family: sans-serif; text-align: center; margin: 8rem;">
    <tr>
        <td>
            <h1></h1>
        </td>
        <td style="text-align: center; margin-left: 0.3rem;">
            <div style="border-bottom: 0.1rem solid black;"></div>
            {{ $usuario['nome'] }}
        </td>
        <td>
            <h1></h1>
        </td>
    </tr>
</table>
