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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/**
 * admin routes
*/
Route::middleware(['auth', 'role:admin'])
  ->prefix('admin')
  ->name('admin.')
  ->namespace('Admin')
  ->group(function() {
  Route::get('dashboard', 'AdminController@dashboard');
});

/**
 * client routes
*/
Route::middleware(['auth', 'role:client'])
  ->prefix('client')
  ->name('client.')
  ->namespace('Client')
  ->group(function() {
    Route::post('home', 'ClientController@savePublish')->name('save_publish');
    Route::get('new_publish', 'ClientController@showNewPublish')->name('new_publish');
    Route::get('edit_publish/{rid}', 'ClientController@showUpdatePublish')->name('edit_publish');
    Route::post('update_publish', 'ClientController@updatePublish')->name('update_publish');
    Route::get('home', 'ClientController@showPublishes')->name('home');

    Route::post('accept_bid', 'ClientController@acceptBid')->name('accept_bid');
    Route::get('show_deposit', 'ClientController@showDeposit')->name('show_deposit');
    Route::get('paypal_deposit', 'ClientController@paypalDeposit')->name('paypal_deposit');
    Route::post('cancel/{rid}', 'ClientController@cancel')->name('cancel');
});

/**
 * designer routes
*/
Route::middleware(['auth', 'role:designer'])
  ->prefix('designer')
  ->name('designer.')
  ->namespace('Designer')
  ->group(function() {
    Route::get('home', 'DesignerController@home')->name('home');
    Route::post('save_bid', 'DesignerController@saveBid')->name('save_bid');
//    Route::get('')
  });
