<?php

use App\Http\Controllers\Configuration\Preventive\ActivityCategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])
    ->prefix('configuracoes')
    ->name('configuracoes.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Categorias de Atividades
        |--------------------------------------------------------------------------
        */

        Route::get('/activity-categories', [
            ActivityCategoryController::class,
            'index',
        ])->name('activity-categories.index');

        Route::get('/activity-categories/criar', [
            ActivityCategoryController::class,
            'create',
        ])->name('activity-categories.create');

        Route::post('/activity-categories', [
            ActivityCategoryController::class,
            'store',
        ])->name('activity-categories.store');

        Route::get('/activity-categories/{activityCategory}/editar', [
            ActivityCategoryController::class,
            'edit',
        ])->name('activity-categories.edit');

        Route::put('/activity-categories/{activityCategory}', [
            ActivityCategoryController::class,
            'update',
        ])->name('activity-categories.update');

        Route::delete('/activity-categories/{activityCategory}', [
            ActivityCategoryController::class,
            'destroy',
        ])->name('activity-categories.destroy');

        Route::patch('/activity-categories/{activityCategory}/ativar', [
            ActivityCategoryController::class,
            'activate',
        ])->name('activity-categories.activate');
    });
