<?php

namespace App\Console;

use App\Models\Usuario;
use App\Services\ClockifyService;
use App\Services\FeriadoService;
use App\Utilitarios;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [];

    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            ClockifyService::sincronizarEntradasClockify(Carbon::today());
        })->everyMinute();

        $schedule->call(function () {
            $usuarios = Usuario::where('ativo', true)
                ->get();

            foreach ($usuarios as $usuario)
            {
                $horas_trabalhadas = Utilitarios::calcularHorasTrabalhadas($usuario, Carbon::today());
                $usuario['banco_horas'] += ($horas_trabalhadas['horas_totais'] - $usuario['carga_horaria']) * 60 + $horas_trabalhadas['minutos_totais'];
                $usuario->save();
            }
        })->daily();

        $schedule->call(function () {
            FeriadoService::sincronizarFeriados(Carbon::today());
        })->yearly();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
