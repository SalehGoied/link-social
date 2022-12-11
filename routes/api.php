<?php

namespace App\Http\Controllers\Api;

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
Route::get('test', [TestController::class, 'test']);

Route::get('/', function(){
    return 'hello (~.~) go to /v1';
});

Route::prefix('v1')->group(function(){

    Route::get('/', function(){
        return 'hello';
    });

    // Auth routes
    Route::controller(AuthController::class)->prefix('/auth')->group(function (){
        Route::post('/register', 'registerUser')->name('auth.register'); // register
        Route::post('/login', 'loginUser')->name('auth.login');     //login
        Route::post('/logout', 'logout')->middleware('auth:sanctum')->name('auth.logout');  // logout
    });
    
    // user
    Route::controller(UserController::class)->prefix('/users')->group(function (){
        Route::get('/', 'index')->name('users.index');  // get all users or search in it with $request->key
        Route::get('/{user}', 'show')->name('user.show');  //show user
        Route::get('/{user}/profile', 'profile')->name('user.profile'); // show profile for user
        Route::get('/{user}/posts', 'posts');   //show posts for user
        Route::put('/',  'update')->middleware('auth:sanctum');     // update user
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
    Route::controller(FollowsController::class)->prefix('/follow')->group(function (){

        Route::post('/{profile}', 'store')->middleware('auth:sanctum');
        Route::get('/{profile}', 'show')->middleware('auth:sanctum');
        Route::get('/{profile}/followers', 'followers');
        Route::get('/{profile}/following', 'following');
        
    });
    

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
            Route::post('/{post}/share', 'share');
            Route::put('/{post}', 'update');
            Route::delete('/{post}', 'delete');
        });
    });

    // post file 
    Route::controller(PhotoController::class)->prefix('/photos')->group(function (){
        Route::get('/post/{post}', 'postPhotos');
        Route::get('/comment/{comment}', 'commentPhotos');
        Route::get('/{photo}', 'show');
        Route::delete('/{photo}', 'delete')->middleware('auth:sanctum');
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

    Route::controller(ReactController::class)->prefix('/reacts')->group(function (){
        Route::get('/post/{post}', 'postReacts');
        Route::get('/comment/{comment}', 'commentReacts');
        Route::get('/{react}', 'show');
        Route::middleware('auth:sanctum')->group(function (){
            Route::post('/post/{post}', 'reactPost');
            Route::post('/comment/{comment}', 'reactComment');
            Route::put('/{react}', 'update');
            // Route::delete('/{react}', 'delete');
        });
    });

    Route::controller(SavedPostController::class)->middleware('auth:sanctum')->prefix('/saved-posts')->group(function (){
        Route::get('', 'index');
        Route::post('/{post}', 'store');
    });

});
