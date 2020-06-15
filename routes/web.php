<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->group(function () {
    Route::get('/', 'GenerateAdministratorController@toGeneratePage');
    Route::get('/generate', 'GenerateAdministratorController@index')->name('admin.generate');
    Route::post('/generate', 'GenerateAdministratorController@store')->name('admin.generate.store');

    Route::middleware('adminExists')->namespace('Admin')->group(function () {
        Route::name('admin')->prefix('login')->group(function () {
            Route::get('/', 'LoginController@index')->name('.login');
            Route::post('/', 'LoginController@check')->name('.login.check');
        });

        Route::middleware('adminLoggedIn')->name('admin')->group(function () {
            Route::get('/dashboard', 'DashboardController@index')->name('.dashboard');
            Route::get('/logout', 'DashboardController@logout')->name('.logout');
        });
    });
});

Route::get('/', function () {
    return view('welcome');
})->name('homePage');
