<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'demoplugin', 'middleware' => ['web']], function () {
    Route::get('/', function () {
        return view('demoplugin::welcome');
    })->name('demoplugin.home');
});
