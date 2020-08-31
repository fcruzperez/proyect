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
        Route::get('dashboard', 'AdminController@dashboard')->name('home');
        Route::get('register', 'AdminController@showRegisterUser')->name('register');
        Route::post('register', 'AdminController@registerUser')->name('register');
        Route::get('settings', 'AdminController@settings')->name('settings');
        Route::post('settings','AdminController@otherSettings')->name('other_settings');
        Route::post('format_new', 'AdminController@formatNew')->name('format.new');
        Route::post('format_update', 'AdminController@formatUpdate')->name('format.update');
        Route::get('format_delete/{id}', 'AdminController@formatDelete')->name('format.delete');
        Route::post('fabric_new', 'AdminController@fabricNew')->name('fabric.new');
        Route::post('fabric_update', 'AdminController@fabricUpdate')->name('fabric.update');
        Route::get('fabric_delete/{id}', 'AdminController@fabricDelete')->name('fabric.delete');
        Route::post('technic_new', 'AdminController@technicNew')->name('technic.new');
        Route::post('technic_update', 'AdminController@technicUpdate')->name('technic.update');
        Route::get('technic_delete/{id}', 'AdminController@technicDelete')->name('technic.delete');

        // withdraw routes
//        Route::get('withdraw-list', 'WithdrawController@list')->name('withdraw-list');
//        Route::get('withdraw-detail', 'WithdrawController@detail')->name('withdraw-detail');
//        Route::get('withdraw-list', 'WithdrawController@list')->name('withdraw.list');
//        Route::get('withdraw-detail/{id}', 'WithdrawController@detail')->name('withdraw.detail');
//        Route::get('withdraw-complete/{id}', 'WithdrawController@complete')->name('withdraw.complete');

        //Score
        Route::get('score', 'AdminController@score')->name('score');
        Route::post('update_score', 'AdminController@updateScore')->name('update_score');
        Route::post('update_publish', 'AdminController@updatePublish')->name('update_publish');
        Route::post('update_mediate', 'AdminController@updateMediateContent')->name('update_mediate');

        //Mediation
//        Route::post('mediation', 'AdminController@mediation')->name('mediation');
        Route::get('mediation', 'AdminController@mediation')->name('mediation');
        Route::get('delivery-download/{id}', 'AdminController@downloadDelivery')->name('delivery-download');
        Route::get('download_errors/{id}', 'AdminController@downloadErrors')->name('download_errors');

        Route::get('refund/{id}', 'AdminController@refund')->name('refund');



    });

/**
 * client routes
 */
Route::middleware(['auth', 'role:client'])
    ->prefix('client')
    ->name('client.')
    ->namespace('Client')
    ->group(function () {

        Route::get('home', 'ClientController@showPublishes')->name('home');
        Route::get('myposts', 'ClientController@listMyPosts')->name('myposts');
        Route::get('new_publish', 'ClientController@showNewPublish')->name('new_publish');
        Route::post('save_publish', 'ClientController@savePublish')->name('save_publish');
        Route::get('edit_publish/{rid}', 'ClientController@showUpdatePublish')->name('edit_publish');
        Route::post('update_publish', 'ClientController@updatePublish')->name('update_publish');
        Route::get('delete_publish/{id}', 'ClientController@deletePublish')->name('delete_publish');

        Route::get('publish-detail/{id}', 'ClientController@publishDetail')->name('publish_detail');
        Route::get('correction/{id}', 'ClientController@seeCorrection')->name('correction');
        Route::get('delivery-download/{id}', 'ClientController@downloadDelivery')->name('delivery_download');
        Route::get('complete-request/{id}', 'ClientController@completeRequest')->name('complete_request');
        Route::post('cancel/{rid}', 'ClientController@cancel')->name('cancel');


        // mediate
        Route::get('mediate-offer/{id}', 'MediateController@new')->name('mediate.new');
        Route::post('mediate-save', 'MediateController@save')->name('mediate.save');
        Route::get('mediate-list', 'MediateController@list')->name('mediate.list');
        Route::get('mediate-detail/{id}', 'MediateController@detail')->name('mediate.detail');
        Route::get('mediate-edit/{id}', 'MediateController@edit')->name('mediate.edit');
        Route::post('mediate-update/{id}', 'MediateController@update')->name('mediate.update');
        Route::get('mediate-complete/{id}', 'MediateController@complete')->name('mediate.complete');
        Route::post('mediate-rejection', 'MediateController@rejection')->name('mediate.rejection');

        Route::post('accept_bid', 'ClientController@acceptBid')->name('accept_bid');
//
        Route::get('show_deposit', 'ClientController@showDeposit')->name('show_deposit');
//
//        // payment routes
//        Route::get('deposit/paypal/{offer_id}', 'ClientController@deposit')->name('deposit');
//        Route::get('deposit-status', 'ClientController@depositStatus')->name('deposit.status');

        // finance routes
        Route::get('finance-list', 'ClientController@financeList')->name('finance.list');


//        Route::get('payment', 'ClientController@deposit')->name('payment');
//        Route::get('payment-cancel', 'ClientController@cancelPayment')->name('payment.cancel');
//        Route::get('payment-success', 'ClientController@successPayment')->name('payment.success');

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
        Route::post('offer-update', 'DesignerController@updateBid')->name('offer-update');
        Route::post('offer-cancel/{id}', 'DesignerController@cancelBid')->name('offer-cancel');
        Route::get('offer-detail/{id}', 'DesignerController@offerDetail')->name('offer-detail');
        Route::get('download-image/{file}', 'DesignerController@downloadImage')->name('download-image');
        Route::post('delivery-upload', 'DesignerController@deliveryUpload')->name('delivery-upload');
//        Route::post('redelivery-upload', 'DesignerController@redeliveryUpload')->name('redelivery-upload');
        Route::get('download_errors/{id}', 'DesignerController@downloadErrors')->name('download_errors');


        // mediate routes
        Route::get('mediate-list', 'MediateController@list')->name('mediate.list');
        Route::get('mediate-detail/{id}', 'MediateController@detail')->name('mediate.detail');
        Route::get('mediate-complete/{id}', 'MediateController@complete')->name('mediate.complete');

        // withdraw routes
//        Route::get('withdraw-list', 'WithdrawController@list')->name('withdraw.list');
//        Route::get('withdraw-new', 'WithdrawController@new')->name('withdraw.new');
//        Route::get('withdraw-detail/{id}', 'WithdrawController@detail')->name('withdraw.detail');
//        Route::post('withdraw-save', 'WithdrawController@save')->name('withdraw.save');

        // Finance
        Route::get('finance-list', 'DesignerController@finance')->name('finance-list');

    });
