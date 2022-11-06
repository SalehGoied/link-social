<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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


Route::get('/', function(){
    return 'hello (~.~) go to /v1';
});

Route::prefix('v1')->group(function(){

    Route::get('/', function(){
        return 'hello';
    });

    Route::controller(AuthController::class)->prefix('/auth')->group(function (){
        Route::post('/register', 'registerUser');
        Route::post('/login', 'loginUser');
        Route::post('/logout', 'logout')->middleware('auth:sanctum');
    });

    Route::put('user/{user}/update', [UserController::class, 'update'])->middleware('auth:sanctum');
});
