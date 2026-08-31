<?php

declare(strict_types=1);

use App\Http\Controllers\Organization\BranchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Filiais
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::resource('filiais', BranchController::class)
        ->parameters([
            'filiais' => 'branch',
        ])
        ->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
        ]);

    Route::patch(
        'filiais/{branch}/toggle-active',
        [BranchController::class, 'toggleActive']
    )->name('filiais.toggle-active');

});
