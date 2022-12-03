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

        return response()->success(
            ['profile' => $profile, 'following'=> $user->following->contains($profile->id),]
            , 'toggel follow');
    }

    /**
     * Show if is  follow
     * 
     * @authenticated
     * @param Profile $profile
     * @return [$profile, 'following']
     */
    public function show(Profile $profile){

        return response()->success(
            ['profile' => $profile, auth()->user()->following->contains($profile->id)]
            , 'Follow');
    }

    /**
     * Show followers
     * 
     * @param Profile $profile
     * @return $followers
     */
    public function followers(Profile $profile){
        return response()->success(['followers'=> $profile->followers->load('profile'),],'followers');
    }

    /**
     * Show following
     * 
     * @param Profile $profile
     * @return $following
     */
    public function following(Profile $profile){
        return response()->success(['following'=> $profile->user->following,],'following');
    }
}
