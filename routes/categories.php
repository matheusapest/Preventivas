<?php

declare(strict_types=1);

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Categorias

|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::resource('categorias', CategoryController::class)
        ->parameters([
            'categorias' => 'category',
        ])
        ->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
        ]);

    Route::patch(
        'categorias/{category}/toggle-active',
        [CategoryController::class, 'toggleActive']
    )->name('categorias.toggle-active');

});
