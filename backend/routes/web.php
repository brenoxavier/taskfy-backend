<?php

use App\Utilitarios;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $pdf = App::make('dompdf.wrapper');
    return $pdf->loadView('relatorio', [
        'relatorio' => [1, 2, 3]
    ])
        ->download('relatorio.pdf');

//    return view('relatorio');
});
