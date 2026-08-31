<?php

use App\Http\Controllers\OperationalProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])
    ->prefix('configuracoes/perfis-operacionais')
    ->name('configuracoes.perfis-operacionais.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Listagem
        |--------------------------------------------------------------------------
        */

        Route::get('/', [
            OperationalProfileController::class,
            'index',
        ])->name('index');

        /*
        |--------------------------------------------------------------------------
        | Cadastro
        |--------------------------------------------------------------------------
        */

        Route::get('/criar', [
            OperationalProfileController::class,
            'create',
        ])->name('create');

        Route::post('/', [
            OperationalProfileController::class,
            'store',
        ])->name('store');

        /*
        |--------------------------------------------------------------------------
        | Edição
        |--------------------------------------------------------------------------
        */

        Route::get('/{operationalProfile}/editar', [
            OperationalProfileController::class,
            'edit',
        ])->name('edit');

        Route::put('/{operationalProfile}', [
            OperationalProfileController::class,
            'update',
        ])->name('update');

        /*
        |--------------------------------------------------------------------------
        | Ativação / Inativação
        |--------------------------------------------------------------------------
        */

        Route::delete('/{operationalProfile}', [
            OperationalProfileController::class,
            'destroy',
        ])->name('destroy');

        Route::patch('/{operationalProfile}/ativar', [
            OperationalProfileController::class,
            'activate',
        ])->name('activate');
    });
