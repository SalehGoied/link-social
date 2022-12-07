<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function($data = null,String $message = '',int $status_code = 200){
            return response()->json([
                'status' => true,
                'message' => $message,
                'data'=> $data
                
            ], $status_code);
        });

        Response::macro('error', function(String $error = '',int $status_code){
            return response()->json([
                'status' => false,
                'error' => $error,
            ], $status_code);
        });
    }
}
