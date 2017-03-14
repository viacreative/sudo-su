<?php

namespace VIACreative\SudoSu;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseServiceProvider;

class RouteServiceProvider extends BaseServiceProvider
{
    public function map()
    {
        Route::group([
            'prefix' => 'sudosu',
            'namespace' => 'VIACreative\SudoSu\Controllers',
            'middleware' => ['web']
        ], function () {
            Route::post('/sudosu/login-as-user', 'SudoSuController@loginAsUser')
                ->name('sudosu.login_as_user');

            Route::post('/sudosu/return', 'SudoSuController@return')
                ->name('sudosu.return');
        });
    }
}