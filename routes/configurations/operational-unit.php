<?php

use App\Http\Controllers\Configuration\Operational\OperationalUnitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])
    ->prefix('configuracoes/unidades-operacionais')
    ->name('configuracoes.unidades-operacionais.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Listagem
        |--------------------------------------------------------------------------
        */

        Route::get('/', [
            OperationalUnitController::class,
            'index',
        ])->name('index');

        /*
        |--------------------------------------------------------------------------
        | Cadastro individual
        |--------------------------------------------------------------------------
        */

        Route::get('/criar', [
            OperationalUnitController::class,
            'create',
        ])->name('create');

        Route::post('/', [
            OperationalUnitController::class,
            'store',
        ])->name('store');

        /*
        |--------------------------------------------------------------------------
        | Cadastro em lote
        |--------------------------------------------------------------------------
        */

        Route::post('/lote', [
            OperationalUnitController::class,
            'storeMultiple',
        ])->name('store-multiple');

        /*
        |--------------------------------------------------------------------------
        | Edição
        |--------------------------------------------------------------------------
        */

        Route::get('/{operationalUnit}/editar', [
            OperationalUnitController::class,
            'edit',
        ])->name('edit');

        Route::put('/{operationalUnit}', [
            OperationalUnitController::class,
            'update',
        ])->name('update');

        /*
        |--------------------------------------------------------------------------
        | Ativação / Inativação
        |--------------------------------------------------------------------------
        */

        Route::delete('/{operationalUnit}', [
            OperationalUnitController::class,
            'destroy',
        ])->name('destroy');

        Route::patch('/{operationalUnit}/ativar', [
            OperationalUnitController::class,
            'activate',
        ])->name('activate');
    });
