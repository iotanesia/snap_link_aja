<?php

use App\Http\Controllers\Api\AuthControler;
use App\Http\Controllers\Api\UserControler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')
->namespace('Api')
->group(function () {

    Route::post('/login',[AuthControler::class,'login']);
    Route::post('/signature',[AuthControler::class,'signature']);

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

