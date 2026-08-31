<?php

use App\Http\Controllers\Configuration\Preventive\PreventiveTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])
    ->prefix('configuracoes/tipos-preventivas')
    ->name('configuracoes.tipos-preventivas.')
    ->group(function () {

        /**
         * --------------------------------------------------------------------------
         * Listagem
         * --------------------------------------------------------------------------
         */
        Route::get('/', [
            PreventiveTypeController::class,
            'index',
        ])->name('index');

        /**
         * --------------------------------------------------------------------------
         * Cadastro
         * --------------------------------------------------------------------------
         */
        Route::get('/criar', [
            PreventiveTypeController::class,
            'create',
        ])->name('create');

        Route::post('/', [
            PreventiveTypeController::class,
            'store',
        ])->name('store');

        /**
         * --------------------------------------------------------------------------
         * Edição
         * --------------------------------------------------------------------------
         */
        Route::get('/{preventiveType}/editar', [
            PreventiveTypeController::class,
            'edit',
        ])->name('edit');

        Route::put('/{preventiveType}', [
            PreventiveTypeController::class,
            'update',
        ])->name('update');

        /**
         * --------------------------------------------------------------------------
         * Ativação / Inativação
         * --------------------------------------------------------------------------
         */
        Route::delete('/{preventiveType}', [
            PreventiveTypeController::class,
            'destroy',
        ])->name('destroy');

        Route::patch('/{preventiveType}/ativar', [
            PreventiveTypeController::class,
            'activate',
        ])->name('activate');
    });
