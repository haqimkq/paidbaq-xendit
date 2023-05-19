<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['verifyXenditCallback'])->group(function () {

    Route::post("/disbursement/notification","Api\\DisbursementController@notification")->name('disbursement.notification');
    Route::post("/account/notification","Api\\XenPlatformController@notification")->name('xenplatform.notification');
    Route::post("/batch-disbursement/notification","Api\\BatchDisbursementController@notification");
});

Route::group(["prefix" => "disbursement", "middleware" => ["paidbaq.auth.basic"]],function(){
    Route::get("/{id}","Api\\DisbursementController@getById")->name('disbursement.index');
    Route::get("/external/{externalId}","Api\\DisbursementController@getByExternalID")->name('disbursement.externalId');
    Route::post("/create","Api\\DisbursementController@createAccount")->name('disbursement.createAccount');
});
Route::group(["prefix" => "batch-disbursement", "as"=> "batch.disbursement", "middleware" => ["paidbaq.auth.basic"]],function(){

    Route::post("/create","Api\\BatchDisbursementController@create")->name('create');
});
Route::group(["prefix" => "account", "middleware" => ["paidbaq.auth.basic"]],function(){
    Route::get("/{account_id}","Api\\XenPlatformController@index")->name('xenplatform.index');
    Route::post("/transfer","Api\\XenPlatformController@transfer")->name('xenplatform.transfer');
    Route::post("/feerule","Api\\XenPlatformController@feeRule")->name('xenplatform.feerule');
    Route::get("/balance/{type}","Api\\XenPlatformController@getBalance")->name('xenplatform.balance');
    Route::put("/{account_id}","Api\\XenPlatformController@updateAccount")->name('xenplatform.update');
    Route::post("/create","Api\\XenPlatformController@createAccount")->name('xenplatform.create');
});
Route::group(["prefix" => "transaction", "middleware" => ["paidbaq.auth.basic"]],function(){
    Route::get("/","Api\\TransactionController@index")->name('transaction.index');
    Route::get("/{id}","Api\\TransactionController@detail")->name('transaction.detail');
});

