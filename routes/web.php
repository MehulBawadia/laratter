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

Route::get('/admin', 'GenerateAdministratorController@toGeneratePage');
Route::get('/admin/generate', 'GenerateAdministratorController@index')->name('admin.generate');
Route::post('/admin/generate', 'GenerateAdministratorController@store')->name('admin.generate.store');

Route::middleware('adminExists')->group(function () {
    Route::prefix('admin')->name('admin')->namespace('Admin')->group(function () {
        Route::prefix('login')->group(function () {
            Route::get('/', 'LoginController@index')->name('.login');
            Route::post('/', 'LoginController@check')->name('.login.check');
        });

        Route::middleware('adminLoggedIn')->group(function () {
            Route::get('/dashboard', 'DashboardController@index')->name('.dashboard');
            Route::get('/logout', 'DashboardController@logout')->name('.logout');

            Route::prefix('account-settings')->group(function () {
                Route::get('/', 'AccountSettingsController@index')->name('.accountSettings');
                Route::patch('/update-general', 'AccountSettingsController@udpateGeneral')
                    ->name('.accountSettings.updateGeneral');
                Route::patch('/change-password', 'AccountSettingsController@changePassword')
                    ->name('.accountSettings.changePassword');
            });
        });
    });

    Route::get('/', 'HomeController@index')->name('homePage');

    Route::name('user')->namespace('User')->group(function () {
        Route::get('/login', 'LoginController@index')->name('.login');
        Route::post('/login', 'LoginController@check')->name('.login.check');

        Route::get('/register', 'RegisterController@index')->name('.register');
        Route::post('/register', 'RegisterController@store')->name('.register.store');

        Route::middleware('userLoggedIn')->group(function () {
            Route::get('/dashboard', 'DashboardController@index')->name('.dashboard');
            Route::get('/logout', 'DashboardController@logout')->name('.logout');
        });
    });
});
