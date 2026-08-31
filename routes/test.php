<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Testes
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/teste/camera',
        function () {
            return view('tests.camera');
        }
    )->name('test.camera');

});
