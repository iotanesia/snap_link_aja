<?php

use App\Http\Controllers\Api\AuthControler;
use App\Http\Controllers\Api\Bri\BriController;
use App\Http\Controllers\Api\Mti\MtiController;
use App\Http\Controllers\Api\SignatureController;
use App\Http\Controllers\Api\UserControler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RSAController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('generate-label',[SignatureController::class,'generateResponseLabel']);

//with middleware
Route::prefix('v1')
->namespace('Api')
->group(function () {
    Route::post('/login',[AuthControler::class,'login']);
    Route::prefix('user')
    ->middleware('admin')
    ->group(function ()
    {
        Route::get('/',[UserControler::class,'getAll']);
        Route::get('/{id}',[UserControler::class,'getById']);
        Route::post('/',[UserControler::class,'save']);
        Route::put('/',[UserControler::class,'update']);
        Route::delete('/{id}',[UserControler::class,'delete']);

    });

    //bri
    Route::prefix('bri')
    ->namespace('Bri')
    ->group(function ()
    {
        Route::post('/signature-auth',[BriController::class,'signatureAuth']);
        // Route::post('/signature-service',[BriController::class,'signatureService']);
        Route::post('/account-inquiry-internal',[BriController::class,'accountInquiryInternal']);

    });

    //mandiri
    Route::prefix('mandiri')
    ->namespace('Mandiri')
    ->group(function ()
    {
        Route::post('/signature-auth',[MandiriController::class,'signatureAuth']);

    });

       //mandiri
       Route::prefix('mti')
       ->namespace('Mti')
       ->group(function ()
       {
           Route::post('/signature-auth',[MtiController::class,'signatureAuth']);

       });


    Route::post('/signature-auth',[SignatureController::class,'create']);
    Route::middleware('signature')->group(function ()
    {
        Route::post('generate-token',[SignatureController::class,'generateToken']);
    });

    Route::post('signature-service',[SignatureController::class,'service']);
    Route::post('card-validation',[SignatureController::class,'cardValidation']);
    Route::post('registration/card-bind-limit',[SignatureController::class,'cardBindLimit']);


});

