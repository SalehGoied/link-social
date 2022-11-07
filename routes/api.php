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

    // Auth routes
    Route::controller(AuthController::class)->prefix('/auth')->group(function (){
        Route::post('/register', 'registerUser');
        Route::post('/login', 'loginUser');
        Route::post('/logout', 'logout')->middleware('auth:sanctum');
    });
    
    // user
    Route::controller(UserController::class)->prefix('/users')->group(function (){
        Route::get('/', 'index');
        Route::get('/{user}', 'show');
        Route::get('/{user}/profile', 'profile');
        Route::put('/',  'update')->middleware('auth:sanctum');
        // todo after asking on it
        Route::delete('/{user}', 'delete')->middleware('auth:sanctum');
    });
    

    // profile
    Route::controller(ProfileController::class)->prefix('/profiles')->group(function (){

        Route::get('/', 'index');
        Route::get('/{profile}', 'show');
        Route::get('/{profile}/user', 'user');
        Route::put('/', 'update')->middleware('auth:sanctum');
        // todo after asking on it
        Route::post('/delete', 'delete');
    });
});
