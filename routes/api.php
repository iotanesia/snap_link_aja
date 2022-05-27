<?php

use App\Http\Controllers\Api\AuthControler;
use App\Http\Controllers\Api\Bri\BriController;
use App\Http\Controllers\Api\Mandiri\MandiriController;
use App\Http\Controllers\Api\RSAController;
use App\Http\Controllers\Api\UserControler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;


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


Route::get('health', HealthCheckJsonResultsController::class);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//with middleware
Route::prefix('v1')
->namespace('Api')
->group(function () {

    Route::post('rsa-file',[RSAController::class,'upload']);
    Route::post('rsa-file-mandiri',[RSAController::class,'uploadMandiri']);

    Route::get('/test',function (Request $request){
       return "service up";
    });

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
        Route::post('/account-inquiry-internal',[BriController::class,'accountInquiryInternal']);
        Route::post('/account-inquiry-status',[BriController::class,'accountInquiryStatus']);
        Route::post('/transfer-intrabank',[BriController::class,'transferIntrabank']);

    });

    //mandiri
    Route::prefix('mandiri')
    ->namespace('Mandiri')
    ->group(function ()
    {
        Route::post('/signature-auth',[MandiriController::class,'signatureAuth']);
        Route::post('/account-inquiry-internal',[MandiriController::class,'accountInquiryInternal']);
        Route::post('/transfer-status',[MandiriController::class,'accountInquiryStatus']);
        Route::post('/transfer-intrabank',[MandiriController::class,'transferIntrabank']);
    });


});

