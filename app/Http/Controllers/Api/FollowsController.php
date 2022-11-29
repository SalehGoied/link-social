<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;


/**
 * @group Follow
 *
 * APIs for follow profile
 */
class FollowsController extends Controller
{

    /**
     * Toggle follow
     * 
     * @authenticated
     * @param Profile $profile
     * @return [$profile, 'following']
     */
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

    /**
     * Show if is  follow
     * 
     * @authenticated
     * @param Profile $profile
     * @return [$profile, 'following']
     */
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

    /**
     * Show followers
     * 
     * @param Profile $profile
     * @return $followers
     */
    public function followers(Profile $profile){

        return response()->json([
            'status' => true,
            'message' => 'followers',
            'data'=> [
                'followers'=> $profile->followers->load('profile'),
            ]
        ], 200);
    }

    /**
     * Show following
     * 
     * @param Profile $profile
     * @return $following
     */
    public function following(Profile $profile){

        return response()->json([
            'status' => true,
            'message' => 'following',
            'data'=> [
                'following'=> $profile->user->following,
            ]
        ], 200);
    }
}
