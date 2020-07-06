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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mail_test', 'MailController@test_email');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/**
 * admin routes
 */
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->namespace('Admin')
    ->group(function () {
        Route::get('dashboard', 'AdminController@dashboard');
    });

/**
 * client routes
 */
Route::middleware(['auth', 'role:client'])
    ->prefix('client')
    ->name('client.')
    ->namespace('Client')
    ->group(function () {
        Route::get('home', 'ClientController@home');
    });

/**
 * designer routes
 */
Route::middleware(['auth', 'role:designer'])
    ->prefix('designer')
    ->name('designer.')
    ->namespace('Designer')
    ->group(function () {
        Route::get('home', 'DesignerController@home');
    });
