<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Platform\CompanyController;

Route::middleware([
    'auth',
    'platform',
])

->prefix('platform')

->name('platform.')

->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::view(
        '/',
        'platform.dashboard'
    )->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Company
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'companies',
        CompanyController::class
    );

});