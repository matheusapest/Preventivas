<?php

use App\Http\Controllers\PreventiveProfileController;
use App\Http\Controllers\PreventiveProfileRuleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])
    ->prefix('configuracoes/perfis-preventivas')
    ->name('configuracoes.perfis-preventivas.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Listagem
        |--------------------------------------------------------------------------
        */

        Route::get('/', [
            PreventiveProfileController::class,
            'index',
        ])->name('index');


        /**
         * --------------------------------------------------------------------------
         * Filiais elegíveis
         * --------------------------------------------------------------------------
         */
        Route::get(
            '/filiais-elegiveis/{preventiveType}',
            [PreventiveProfileController::class, 'eligibleBranches']
        )->name('filiais.eligible');
        /*
        |--------------------------------------------------------------------------
        | Cadastro
        |--------------------------------------------------------------------------
        */



        Route::get('/criar', [
            PreventiveProfileController::class,
            'create',
        ])->name('create');

        Route::post('/', [
            PreventiveProfileController::class,
            'store',
        ])->name('store');

        /*
        |--------------------------------------------------------------------------
        | Dados auxiliares
        |--------------------------------------------------------------------------
        */

        Route::get('/dados/formulario', [
            PreventiveProfileController::class,
            'formData',
        ])->name('form-data');

        /*
        |--------------------------------------------------------------------------
        | Edição
        |--------------------------------------------------------------------------
        */

        Route::get('/{preventiveProfile}/editar', [
            PreventiveProfileController::class,
            'edit',
        ])->name('edit');

        Route::put('/{preventiveProfile}', [
            PreventiveProfileController::class,
            'update',
        ])->name('update');

        /*
        |--------------------------------------------------------------------------
        | Ativação / Inativação
        |--------------------------------------------------------------------------
        */

        Route::patch('/{preventiveProfile}/toggle-active', [
            PreventiveProfileController::class,
            'toggleActive',
        ])->name('toggle-active');

        /*
        |--------------------------------------------------------------------------
        | Regras do Perfil
        |--------------------------------------------------------------------------
        */

        Route::get('/{preventiveProfile}/regras', [
            PreventiveProfileRuleController::class,
            'index',
        ])->name('regras.index');

        Route::get('/{preventiveProfile}/regras/criar', [
            PreventiveProfileRuleController::class,
            'create',
        ])->name('regras.create');

        Route::post('/{preventiveProfile}/regras', [
            PreventiveProfileRuleController::class,
            'store',
        ])->name('regras.store');

        /*
        |--------------------------------------------------------------------------
        | Visualização da regra
        |--------------------------------------------------------------------------
        */

        Route::get('/{preventiveProfile}/regras/{rule}', [
            PreventiveProfileRuleController::class,
            'show',
        ])->name('regras.show');

        /**
         * --------------------------------------------------------------------------
         * Edição da regra
         * --------------------------------------------------------------------------
         */
        Route::get('/{preventiveProfile}/regras/{rule}/editar', [
            PreventiveProfileRuleController::class,
            'edit',
        ])->name('regras.edit');

        Route::put('/{preventiveProfile}/regras/{rule}', [
            PreventiveProfileRuleController::class,
            'update',
        ])->name('regras.update');

        /**
         * --------------------------------------------------------------------------
         * Regras específicas
         * --------------------------------------------------------------------------
         */

        Route::post(
            '/{preventiveProfile}/regras/{rule}/especifica',
            [PreventiveProfileRuleController::class, 'storeSpecific']
        )->name('regras.specific.store');

        Route::put(
            '/{preventiveProfile}/regras/{rule}/especifica/{specificRule}',
            [PreventiveProfileRuleController::class, 'updateSpecific']
        )->name('regras.specific.update');

        Route::put(
            '/{preventiveProfile}/regras/{rule}/especifica/{specificRule}/show',
            [PreventiveProfileRuleController::class, 'updateSpecificFromShow']
        )->name('regras.specific.update-from-show');

        Route::delete(
            '/{preventiveProfile}/regras/{rule}/especifica/{specificRule}',
            [PreventiveProfileRuleController::class, 'destroySpecific']
        )->name('regras.specific.destroy');

        /**
         * --------------------------------------------------------------------------
         * Configuração completa da filial
         * --------------------------------------------------------------------------
         */
        Route::delete(
            '/{preventiveProfile}/regras/filiais/{profileBranch}',
            [PreventiveProfileRuleController::class, 'destroyBranchConfiguration']
        )->name('regras.branch.destroy');
    });
