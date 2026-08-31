<?php

declare(strict_types=1);

use App\Http\Controllers\Organization\CompanyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Empresas
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::resource('empresas', CompanyController::class)
        ->parameters([
            'empresas' => 'company',
        ])
        ->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
        ]);

    Route::patch(
        'empresas/{company}/toggle-active',
        [CompanyController::class, 'toggleActive']
    )->name('empresas.toggle-active');

});
