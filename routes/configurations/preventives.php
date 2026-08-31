<?php

use App\Http\Controllers\Configuration\Preventive\PreventiveController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('preventivas')
    ->name('preventivas.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Listagem
        |--------------------------------------------------------------------------
        */

        Route::get('/', [
            PreventiveController::class,
            'index',
        ])->name('index');


        /*
        |--------------------------------------------------------------------------
        | Dados auxiliares
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dados/tipos/{branch}',
            [PreventiveController::class, 'types']
        )->name('types');

        Route::get(
            '/dados/perfis/{branch}/{preventiveType}',
            [PreventiveController::class, 'profiles']
        )->name('profiles');

        Route::get(
            '/dados/perfis/{preventiveProfile}/estrutura',
            [PreventiveController::class, 'profileStructure']
        )->name('profile-structure');

        Route::get(
            '/dados/configuracao/{branch}/{preventiveProfile}',
            [PreventiveController::class, 'configuration']
        )->name('configuration');

        Route::get(
            '/dados/unidades/{branch}/{preventiveType}',
            [PreventiveController::class, 'eligibleUnits']
        )->name('eligible-units');


        /*
        |--------------------------------------------------------------------------
        | Cadastro
        |--------------------------------------------------------------------------
        */

        Route::middleware('role:admin')
            ->group(function () {

                Route::get('/criar', [
                    PreventiveController::class,
                    'create',
                ])->name('create');

                Route::post('/', [
                    PreventiveController::class,
                    'store',
                ])->name('store');
            });


        /*
|--------------------------------------------------------------------------
| Validação da preventiva
|--------------------------------------------------------------------------
*/

        Route::get(
            '/{preventive}/validacao',
            [PreventiveController::class, 'validate']
        )->name('validation');

        Route::post(
            '/{preventive}/aprovar',
            [PreventiveController::class, 'approve']
        )->name('approve');

        Route::post(
            '/{preventive}/reprovar',
            [PreventiveController::class, 'reject']
        )->name('reject');


        /*
|--------------------------------------------------------------------------
| Continuidade da preventiva
|--------------------------------------------------------------------------
*/

        Route::get(
            '/{preventive}/continuidade',
            [PreventiveController::class, 'continuation']
        )->name('continuation');

        Route::post(
            '/{preventive}/continuidade',
            [PreventiveController::class, 'storeContinuation']
        )->name('continuation.store');
        /*
|--------------------------------------------------------------------------
| Dados da continuidade
|--------------------------------------------------------------------------
*/
        Route::get(
    '/{preventive}/continuidade/unidades',
    [PreventiveController::class, 'continuationUnits']
)->name('continuation.units');

        Route::get(
            '/{preventive}/continuidade/unidades/{operationalUnitId}/atividades',
            [PreventiveController::class, 'continuationActivities']
        )->name('continuation.activities');


        /*
        |--------------------------------------------------------------------------
        | Visualização
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{preventive}',
            [PreventiveController::class, 'show']
        )->name('show');
    });
