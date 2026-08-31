<?php

declare(strict_types=1);

use App\Http\Controllers\Equipment\EquipmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Equipamentos
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::resource('equipamentos', EquipmentController::class)
        ->parameters([
            'equipamentos' => 'equipment',
        ])
        ->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
        ]);

    Route::patch(
        'equipamentos/{equipment}/toggle-active',
        [EquipmentController::class, 'toggleActive']
    )->name('equipamentos.toggle-active');

    Route::get(
        'equipamentos/buscar',
        [EquipmentController::class, 'search']
    )->name('equipamentos.search');

});
