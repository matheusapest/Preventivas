<?php

declare(strict_types=1);

use App\Http\Controllers\MaintenanceReceiptController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API - Reparo Externo
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Busca de equipamento para recebimento
    |--------------------------------------------------------------------------
    */

    Route::get(
        'reparos-externos/recebimentos/buscar',
        [MaintenanceReceiptController::class, 'search']
    )->name(
        'reparos_externos.recebimentos.search'
    );

});
