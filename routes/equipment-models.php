<?php

declare(strict_types=1);

use App\Http\Controllers\EquipmentModelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Modelos de Equipamentos
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::resource('modelos-equipamentos', EquipmentModelController::class)
        ->parameters([
            'modelos-equipamentos' => 'equipmentModel',
        ])
        ->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
        ]);

    Route::patch(
        'modelos-equipamentos/{equipmentModel}/toggle-active',
        [EquipmentModelController::class, 'toggleActive']
    )->name('modelos-equipamentos.toggle-active');

});
