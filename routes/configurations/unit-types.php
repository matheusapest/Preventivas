<?php

use App\Http\Controllers\Configuration\Operational\UnitTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])
    ->prefix('configuracoes/tipos-unidade')
    ->name('configuracoes.tipos-unidade.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Listagem
        |--------------------------------------------------------------------------
        */

        Route::get('/', [
            UnitTypeController::class,
            'index',
        ])->name('index');

        /*
        |--------------------------------------------------------------------------
        | Cadastro
        |--------------------------------------------------------------------------
        */

        Route::get('/criar', [
            UnitTypeController::class,
            'create',
        ])->name('create');

        Route::post('/', [
            UnitTypeController::class,
            'store',
        ])->name('store');

        /*
        |--------------------------------------------------------------------------
        | Edição
        |--------------------------------------------------------------------------
        */

        Route::get('/{unitType}/editar', [
            UnitTypeController::class,
            'edit',
        ])->name('edit');

        Route::put('/{unitType}', [
            UnitTypeController::class,
            'update',
        ])->name('update');

        /*
        |--------------------------------------------------------------------------
        | Ativação / Inativação
        |--------------------------------------------------------------------------
        */

        Route::delete('/{unitType}', [
            UnitTypeController::class,
            'destroy',
        ])->name('destroy');

        Route::patch('/{unitType}/ativar', [
            UnitTypeController::class,
            'activate',
        ])->name('activate');
    });
