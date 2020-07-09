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
        Route::get('settings', 'AdminController@settings')->name('settings');
//  Route::post('save_settings', 'AdminController@saveSettings')->name('save_settings');


        Route::post('format_new', 'AdminController@formatNew')->name('format.new');
        Route::post('format_update', 'AdminController@formatUpdate')->name('format.update');
        Route::get('format_delete/{id}', 'AdminController@formatDelete')->name('format.delete');
        Route::post('fabric_new', 'AdminController@fabricNew')->name('fabric.new');
        Route::post('fabric_update', 'AdminController@fabricUpdate')->name('fabric.update');
        Route::get('fabric_delete/{id}', 'AdminController@fabricDelete')->name('fabric.delete');
        Route::post('technic_new', 'AdminController@technicNew')->name('technic.new');
        Route::post('technic_update', 'AdminController@technicUpdate')->name('technic.update');
        Route::get('technic_delete/{id}', 'AdminController@technicDelete')->name('technic.delete');

    });

/**
 * client routes
 */
Route::middleware(['auth', 'role:client'])
    ->prefix('client')
    ->name('client.')
    ->namespace('Client')
    ->group(function () {
        Route::post('home', 'ClientController@savePublish')->name('save_publish');
        Route::get('home', 'ClientController@showPublishes')->name('home');
        Route::get('new_publish', 'ClientController@showNewPublish')->name('new_publish');
        Route::get('edit_publish/{rid}', 'ClientController@showUpdatePublish')->name('edit_publish');
        Route::post('update_publish', 'ClientController@updatePublish')->name('update_publish');
        Route::get('publish-detail/{id}', 'ClientController@publishDetail')->name('publish_detail');
        Route::get('delivery-download/{id}', 'ClientController@downloadDelivery')->name('delivery_download');
        Route::get('mediate-offer/{id}', 'ClientController@mediateOffer')->name('mediate_offer');
        Route::get('complete-request/{id}', 'ClientController@completeRequest')->name('complete_request');

        Route::post('accept_bid', 'ClientController@acceptBid')->name('accept_bid');
        Route::get('show_deposit', 'ClientController@showDeposit')->name('show_deposit');
        Route::post('cancel/{rid}', 'ClientController@cancel')->name('cancel');

        // payment routes
        Route::get('deposit/paypal/{offer_id}', 'ClientController@deposit')->name('deposit');
        Route::get('deposit/cancel', 'ClientController@deposit_cancel')->name('deposit.cancel');
        Route::get('deposit/success', 'ClientController@deposit_success')->name('deposit.success');
    });

/**
 * designer routes
 */
Route::middleware(['auth', 'role:designer'])
    ->prefix('designer')
    ->name('designer.')
    ->namespace('Designer')
    ->group(function () {
        Route::get('home', 'DesignerController@home')->name('home');
        Route::get('posts', 'DesignerController@viewPosts')->name('posts');
        Route::post('offer-save', 'DesignerController@saveBid')->name('offer-save');
        Route::get('offer-cancel/{id}', 'DesignerController@cancelBid')->name('offer-cancel');
        Route::get('offer-detail/{id}', 'DesignerController@offerDetail')->name('offer-detail');
        Route::get('download-image/{file}', 'DesignerController@downloadImage')->name('download-image');
        Route::put('delivery-upload', 'DesignerController@deliveryUpload')->name('delivery-upload');
    });
