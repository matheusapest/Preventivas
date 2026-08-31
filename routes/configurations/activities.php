<?php

use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])
    ->prefix('configuracoes/tipos-preventivas')
    ->name('configuracoes.tipos-preventivas.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Atividades
        |--------------------------------------------------------------------------
        |
        | As atividades pertencem a um tipo de preventiva.
        |
        */

        // Lista as atividades de um tipo de preventiva.
        Route::get('/{preventiveType}/atividades', [
            ActivityController::class,
            'index',
        ])->name('activities.index');

        // Exibe o formulário para criar uma nova atividade.
        Route::get('/{preventiveType}/atividades/criar', [
            ActivityController::class,
            'create',
        ])->name('activities.create');

        // Armazena uma nova atividade.
        Route::post('/{preventiveType}/atividades', [
            ActivityController::class,
            'store',
        ])->name('activities.store');

        // Exibe o formulário para editar uma atividade.
        Route::get('/{preventiveType}/atividades/{activity}/editar', [
            ActivityController::class,
            'edit',
        ])->name('activities.edit');

        // Atualiza uma atividade.
        Route::put('/{preventiveType}/atividades/{activity}', [
            ActivityController::class,
            'update',
        ])->name('activities.update');

        // Exibe os detalhes de uma atividade.
        Route::get('/{preventiveType}/atividades/{activity}', [
            ActivityController::class,
            'show',
        ])->name('activities.show');

        // Inativa uma atividade.
        Route::delete('/{preventiveType}/atividades/{activity}', [
            ActivityController::class,
            'destroy',
        ])->name('activities.destroy');

        // Ativa uma atividade.
        Route::patch('/{preventiveType}/atividades/{activity}/ativar', [
            ActivityController::class,
            'activate',
        ])->name('activities.activate');
    });
