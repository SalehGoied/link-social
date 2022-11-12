<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\TestController;

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
Route::post('test', [TestController::class, 'testUploadeImages']);

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
        Route::get('/{user}/posts', 'posts');
        Route::put('/',  'update')->middleware('auth:sanctum');
        // todo after asking on it
        // Route::delete('/', 'delete')->middleware('auth:sanctum');
    });
    

    // profile
    Route::controller(ProfileController::class)->prefix('/profiles')->group(function (){

        Route::get('/', 'index');
        Route::get('/{profile}', 'show');
        Route::get('/{profile}/user', 'showUser');
        Route::get('/{profile}/images', 'showImages');
        Route::put('/', 'update')->middleware('auth:sanctum');
        // todo after asking on it
        // Route::delete('/', 'delete');
    });

    // follow
    Route::post('/follow/{profile}', [FollowsController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/follow/{profile}', [FollowsController::class, 'show'])->middleware('auth:sanctum');

    // profile image
    Route::controller(ProfileImageController::class)->prefix('/images')->group(function (){

        Route::get('/profile/{profile}', 'index');
        Route::get('/{profileImage}', 'show');
        // Route::put('/{profileImage}', 'update')->middleware('auth:sanctum');
        Route::delete('/{profileImage}', 'delete')->middleware('auth:sanctum');
    });

    // posts
    Route::controller(PostController::class)->prefix('/posts')->group(function (){

        Route::get('/', 'index');
        Route::get('user/{user}', 'showUserPosts');
        Route::get('/{post}', 'show');
        Route::middleware('auth:sanctum')->group(function (){
            Route::post('/', 'store');
            Route::put('/{post}', 'update');
            Route::delete('/{post}', 'delete');
        });
    });


    // post file 
    Route::controller(PostFileController::class)->prefix('/files')->group(function (){

        Route::get('/post/{post}', 'index');
        Route::get('/{postFile}', 'show');
        Route::delete('/{postFile}', 'delete')->middleware('auth:sanctum');
    });

    // comments 
    Route::controller(CommentController::class)->prefix('/comments')->group(function (){

        Route::get('/post/{post}', 'index');
        Route::get('/{comment}', 'show');
        Route::middleware('auth:sanctum')->group(function (){
            Route::post('/{post}', 'store');
            Route::put('/{comment}', 'update');
            Route::delete('/{comment}', 'delete');
        });
    });


});
