<?php

use App\Http\Controllers\Api\AuthControler;
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


Route::get('/create-signature',[RSAController::class, 'rsa']);
Route::get('/verify_rsa',[RSAController::class, 'verify_rsa']);
Route::get('/verify_rsa_test',[RSAController::class, 'verify_rsa_test']);

//with middleware
Route::prefix('v1')
->namespace('Api')
->group(function () {
    Route::post('/login',[AuthControler::class,'login']);
    Route::post('/signature-auth',[SignatureController::class,'create']);
    Route::middleware('signature')->group(function ()
    {
        Route::post('generate-token',[SignatureController::class,'generateToken']);
    });

    Route::post('signature-service',[SignatureController::class,'service']);
    Route::post('card-validation',[SignatureController::class,'generateResponseLabel']);

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
});

