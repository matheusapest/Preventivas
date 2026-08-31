<?php

declare(strict_types=1);

use App\Http\Controllers\PreventiveExecutionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Execução
    |--------------------------------------------------------------------------
    */

    Route::get(
        'preventivas/execucao',
        [PreventiveExecutionController::class, 'index']
    )->name('preventivas.execucao.index');


    /*
    |--------------------------------------------------------------------------
    | Visualização da execução
    |--------------------------------------------------------------------------
    */

    Route::get(
        'preventivas/{preventive}/execucao',
        [PreventiveExecutionController::class, 'show']
    )->name('preventivas.execucao.show');

    /**
     * --------------------------------------------------------------------------
     * Evidência fotográfica da atividade
     * --------------------------------------------------------------------------
     */
    Route::get(
        'preventivas/{preventive}/execucao/resposta/{response}/foto',
        [PreventiveExecutionController::class, 'responsePhoto']
    )->name('preventivas.execucao.response-photo');
    /*
    |--------------------------------------------------------------------------
    | Formulário da atividade
    |--------------------------------------------------------------------------
    */

    Route::get(
        'preventivas/{preventive}/execucao/unidade/{cycleUnit}/atividade/{activity}',
        [PreventiveExecutionController::class, 'activity']
    )->name('preventivas.execucao.activity');


    /*
    |--------------------------------------------------------------------------
    | Persistência da resposta da atividade
    |--------------------------------------------------------------------------
    */

    Route::post(
        'preventivas/{preventive}/execucao/unidade/{cycleUnit}/atividade/{activity}',
        [PreventiveExecutionController::class, 'storeActivityResponse']
    )->name('preventivas.execucao.activity.store');


    /*
    |--------------------------------------------------------------------------
    | Finalização do Cycle pelo Técnico
    |--------------------------------------------------------------------------
    */

    Route::post(
        'preventivas/{preventive}/execucao/finalizar-pendente',
        [PreventiveExecutionController::class, 'finalizeWithPending']
    )->name('preventivas.execucao.finalize-with-pending');
});
