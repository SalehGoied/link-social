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
     * @return $following
     */
    public function store(Profile $profile){
        /**
         * @var $user
         */
        $user = auth()->user();
        $user->following()->toggle($profile);

        return response()->success(
            ['following'=> $user->following->contains($profile->id),]
            , 'Toggel follow');
    }

    /**
     * Show if is  follow
     * 
     * @authenticated
     * @param Profile $profile
     * @return $following
     */
    public function show(Profile $profile){

        return response()->success(
            ['following'=> auth()->user()->following->contains($profile->id)]
            , 'Follow');
    }

    /**
     * Show followers
     * 
     * @param Profile $profile
     * @return $followers
     */
    public function followers(Profile $profile){
        return response()->success(['followers'=> $profile->followers->load('profile'),],'Followers');
    }

    /**
     * Show following
     * 
     * @param Profile $profile
     * @return $following
     */
    public function following(Profile $profile){
        return response()->success(['following'=> $profile->user->following,],'Following');
    }
}
