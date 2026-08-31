<?php

declare(strict_types=1);

use App\Http\Controllers\Organization\BranchCodeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Números de Filiais
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::resource('codigos-filiais', BranchCodeController::class)
        ->parameters([
            'codigos-filiais' => 'branchCode',
        ])
        ->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
        ]);

    Route::patch(
        'codigos-filiais/{branchCode}/toggle-active',
        [BranchCodeController::class, 'toggleActive']
    )->name('codigos-filiais.toggle-active');

});
