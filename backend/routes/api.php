<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClockifyController;
use App\Http\Controllers\EntradaTempoController;
use App\Http\Controllers\FeriadoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Middleware\Administrador;
use App\Http\Middleware\Ativo;
use Illuminate\Support\Facades\Route;

Route::post('autenticar', [AuthController::class, 'autenticar']);
Route::get('relatorio/{id_usuario?}', [EntradaTempoController::class, 'relatorio']);

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware(Ativo::class)->group(function () {
        Route::middleware(Administrador::class)->group(function () {
            Route::get('clockify/usuarios', [ClockifyController::class, 'exibirUsuarios']);

            Route::prefix('usuarios')->group(function () {
                Route::get('/{id_usuario?}', [UsuarioController::class, 'exibir']);
                Route::post('/', [UsuarioController::class, 'salvar']);
                Route::put('/{id_usuario}', [UsuarioController::class, 'editar']);
                Route::delete('/{id_usuario}', [UsuarioController::class, 'excluir']);
            });

            Route::prefix('entrada')->group(function () {
                Route::post('/', [EntradaTempoController::class, 'cadastrar']);
                Route::put('editar/{id_entrada}', [EntradaTempoController::class, 'editar']);
                Route::delete('/{id_entrada}', [EntradaTempoController::class, 'deletar']);
            });

            Route::prefix('feriado')->group(function () {
                Route::get('listar', [FeriadoController::class, 'listar']);
                Route::post('/', [FeriadoController::class, 'cadastrar']);
                Route::put('/{id_feriado}', [FeriadoController::class, 'editar']);
                Route::delete('/{id_feriado}', [FeriadoController::class, 'deletar']);
            });

            Route::get('token', [AuthController::class, 'gerarTokenTemporario']);
        });
    });
});
