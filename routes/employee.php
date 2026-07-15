<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'employee',
])

->prefix('employee')

->name('employee.')

->group(function () {

    Route::view(
        '/',
        'employee.dashboard'
    )->name('dashboard');

});