<?php

declare(strict_types=1);

use App\Http\Controllers\Maintenance\MaintenanceOrderController;
use App\Http\Controllers\Maintenance\MaintenanceReceiptController;
use App\Http\Controllers\Maintenance\MaintenanceShipmentController;
use App\Http\Controllers\Maintenance\MaintenanceValidationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Reparo Externo
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Painel de Ordens de Serviço
    |--------------------------------------------------------------------------
    */

    Route::get(
        'reparos-externos',
        [MaintenanceOrderController::class, 'index']
    )->name('reparos_externos.index');


    /*
    |--------------------------------------------------------------------------
    | Envio de Equipamento
    |--------------------------------------------------------------------------
    */

    Route::get(
        'reparos-externos/enviar',
        [MaintenanceShipmentController::class, 'create']
    )->name('reparos_externos.create');

    Route::post(
        'reparos-externos/envios',
        [MaintenanceShipmentController::class, 'store']
    )->name('reparos_externos.store');


    /*
    |--------------------------------------------------------------------------
    | Edição dos Dados Logísticos do Envio
    |--------------------------------------------------------------------------
    */

    Route::get(
        'reparos-externos/envios/{maintenanceShipment}/editar',
        [MaintenanceShipmentController::class, 'edit']
    )->name('reparos_externos.envios.editar.form');

    Route::put(
        'reparos-externos/envios/{maintenanceShipment}',
        [MaintenanceShipmentController::class, 'update']
    )->name('reparos_externos.envios.editar.update');


    /*
    |--------------------------------------------------------------------------
    | Reenvio de Equipamento
    |--------------------------------------------------------------------------
    */

    Route::get(
        'reparos-externos/{maintenanceOrder}/reenviar',
        [MaintenanceShipmentController::class, 'resend']
    )->name('reparos_externos.reenviar.form');

    Route::post(
        'reparos-externos/{maintenanceOrder}/reenviar',
        [MaintenanceShipmentController::class, 'storeResend']
    )->name('reparos_externos.reenviar.store');


    /*
    |--------------------------------------------------------------------------
    | Recebimento de Equipamentos
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Painel de Recebimentos Pendentes
    |--------------------------------------------------------------------------
    */

    Route::get(
        'reparos-externos/recebimentos',
        [MaintenanceReceiptController::class, 'pending']
    )->name('reparos_externos.recebimentos.index');


    /*
    |--------------------------------------------------------------------------
    | Recebimento Múltiplo de Equipamentos
    |--------------------------------------------------------------------------
    */

    Route::get(
        'reparos-externos/receber',
        [MaintenanceReceiptController::class, 'multiple']
    )->name('reparos_externos.recebimentos.multiplos');

    Route::post(
        'reparos-externos/receber',
        [MaintenanceReceiptController::class, 'storeMultiple']
    )->name('reparos_externos.recebimentos.multiplos.store');


    /*
    |--------------------------------------------------------------------------
    | Recebimento Individual de Equipamento
    |--------------------------------------------------------------------------
    */

    Route::get(
        'reparos-externos/envios/{maintenanceShipment}/receber',
        [MaintenanceReceiptController::class, 'receive']
    )->name('reparos_externos.receber.form');

    Route::post(
        'reparos-externos/envios/{maintenanceShipment}/receber',
        [MaintenanceReceiptController::class, 'store']
    )->name('reparos_externos.receber.store');


    /*
    |--------------------------------------------------------------------------
    | Edição dos Dados Logísticos do Recebimento
    |--------------------------------------------------------------------------
    */

    Route::get(
        'reparos-externos/recebimentos/{maintenanceReceipt}/editar',
        [MaintenanceReceiptController::class, 'edit']
    )->name('reparos_externos.recebimentos.editar.form');

    Route::put(
        'reparos-externos/recebimentos/{maintenanceReceipt}',
        [MaintenanceReceiptController::class, 'update']
    )->name('reparos_externos.recebimentos.editar.update');


    /*
    |--------------------------------------------------------------------------
    | Validação do Reparo
    |--------------------------------------------------------------------------
    */

    Route::get(
        'reparos-externos/recebimentos/{maintenanceReceipt}/validar',
        [MaintenanceValidationController::class, 'create']
    )->name('reparos_externos.validar.form');

    Route::post(
        'reparos-externos/recebimentos/{maintenanceReceipt}/validar',
        [MaintenanceValidationController::class, 'store']
    )->name('reparos_externos.validar.store');


    /*
    |--------------------------------------------------------------------------
    | Impressão da Ordem de Serviço
    |--------------------------------------------------------------------------
    */

    Route::get(
        'reparos-externos/{maintenanceOrder}/pdf',
        [MaintenanceOrderController::class, 'pdf']
    )->name('reparos_externos.os.pdf');


    /*
    |--------------------------------------------------------------------------
    | Visualização da Ordem de Serviço
    |--------------------------------------------------------------------------
    */

    Route::get(
        'reparos-externos/{maintenanceOrder}',
        [MaintenanceOrderController::class, 'show']
    )->name('reparos_externos.show');
});
