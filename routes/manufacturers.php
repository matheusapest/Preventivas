<?php

declare(strict_types=1);

use App\Http\Controllers\Equipment\ManufacturerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Fabricantes

|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::resource('fabricantes', ManufacturerController::class)
        ->parameters([
            'fabricantes' => 'manufacturer',
        ])
        ->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
        ]);

    Route::patch(
        'fabricantes/{manufacturer}/toggle-active',
        [ManufacturerController::class, 'toggleActive']
    )->name('fabricantes.toggle-active');

});
