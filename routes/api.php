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

Route::group(["prefix" => "disbursement", "middleware" => ["paidbaq.auth.basic"]],function(){
    Route::get("/{id}","Api\\DisbursementController@getById")->name('disbursement.index');
    Route::get("/external/{externalId}","Api\\DisbursementController@getByExternalID")->name('disbursement.externalId');
    Route::post("/create","Api\\DisbursementController@createAccount")->name('disbursement.createAccount');
    Route::post("/notification","Api\\DisbursementController@notification")->name('xenplatform.notification');
});
Route::group(["prefix" => "xenplatform", "middleware" => ["paidbaq.auth.basic"]],function(){
    Route::get("/{account_id}","Api\\XenPlatformController@index")->name('xenplatform.index');
    Route::post("/transfer","Api\\XenPlatformController@transfer")->name('xenplatform.transfer');
    Route::post("/feerule","Api\\XenPlatformController@feeRule")->name('xenplatform.feerule');
    Route::get("/balance/{type}","Api\\XenPlatformController@getBalance")->name('xenplatform.balance');
    Route::put("/{account_id}","Api\\XenPlatformController@updateAccount")->name('xenplatform.update');
    Route::post("/create","Api\\XenPlatformController@createAccount")->name('xenplatform.create');
    Route::post("/notification","Api\\XenPlatformController@notification")->name('xenplatform.notification');
});
Route::group(["prefix" => "transaction", "middleware" => ["paidbaq.auth.basic"]],function(){
    Route::get("/","Api\\TransactionController@index")->name('transaction.index');
    Route::get("/{id}","Api\\TransactionController@detail")->name('transaction.detail');
});
