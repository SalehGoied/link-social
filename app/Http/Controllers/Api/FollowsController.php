<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class FollowsController extends Controller
{
    public function store(Profile $profile){
        /**
         * @var $user
         */
        $user = auth()->user();
        $user->following()->toggle($profile);

        return response()->json([
            'status' => true,
            'message' => 'toggel follow',
            'data'=>[
                'profile' => $profile,
                'following'=> $user->following->contains($profile->id),
            ],
        ], 200);
    }


    public function show(Profile $profile){

        return response()->json([
            'status' => true,
            'message' => 'follow',
            'data'=> [
                'profile' => $profile,
                'following'=> auth()->user()->following->contains($profile->id),
            ]
        ], 200);
    }
}
