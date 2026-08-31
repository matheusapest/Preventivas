<?php

declare(strict_types=1);

use App\Http\Controllers\Transfer\TransferController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Transferências
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Consulta de Equipamentos
    |--------------------------------------------------------------------------
    */

    Route::get(
        'transferencias',
        [TransferController::class, 'index']
    )->name('transferencias.index');

    /*
    |--------------------------------------------------------------------------
    | Envio de Equipamentos
    |--------------------------------------------------------------------------
    */

    Route::get(
        'transferencias/enviar',
        [TransferController::class, 'create']
    )->name('transferencias.create');

    Route::post(
        'transferencias',
        [TransferController::class, 'store']
    )->name('transferencias.store');

    /*
    |--------------------------------------------------------------------------
    | Recebimento de Equipamentos
    |--------------------------------------------------------------------------
    */

    Route::get(
        'transferencias/receber',
        [TransferController::class, 'receiveIndex']
    )->name('transferencias.receive.index');

    Route::post(
        'transferencias/{transfer}/receber',
        [TransferController::class, 'receive']
    )->name('transferencias.receive');

    Route::get(
        'transferencias/consultar',
        [TransferController::class, 'search']
    )->name('transferencias.search');

    Route::get(
        '/mobile/transferencias/consultar',
        function () {
            return view('transfers.mobile.search');
        }
    );
});
