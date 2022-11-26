<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use App\Models\ProfileImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


/**
 * @group profile
 *
 * APIs for profiles
 */
class ProfileController extends Controller
{

    /**
     * profiles
     * 
     * 
     * @return $profiles
     */
    public function index(Request $request){
        return response()->json([
            'status' => true,
            'message' => 'Profiles',
            'data'=>[
                'profiles' => Profile::all(),
            ],
        ], 200);
    }

    /**
     * show profile
     * 
     * @param Profile $profile
     * @return Profile
     */
    public function show(Request $request, Profile $profile){
        return response()->json([
            'status' => true,
            'message' => 'Profile',
            'data'=> [
                'profile' => $profile->load('followers', 'user.following'),
                // 'followers' => $profile->followers,
                // 'following' => $profile->user->following,
            ],
        ], 200);
    }

    /**
     * show user for profile
     * 
     * @param Profile $profile
     * @return User
     */

    public function showUser(Request $request, Profile $profile){
        return response()->json([
            'status' => true,
            'message' => 'User for Profile',
            'data'=>[
                'user' => $profile->user,
            ],
        ], 200);
    }


    /**
     * update profile
     * 
     * 
     * @authenticated
     * @param Request $request
     * @return Profile
     */
    public function update(UpdateProfileRequest $request){

        $profile = auth()->user()->profile;

        if ($request->hasFile('avatar')){
            $path_avatat = $this->image($request->file('avatar'), 'avatar', $profile->id);
        }
        else{
            $path_avatat = $profile->avatar;
        }

        if ($request->hasFile('cover')){
            $path_cover = $this->image($request->file('cover'), 'cover', $profile->id);
        }
        else{
            $path_cover = $profile->avatar;
        }
        auth()->user()->profile->update([
            'description' => $request->description ? :$profile->description,
            'avatar' => $path_avatat,
            'cover' => $path_cover,
        ]);

        return response($profile);
    }


    /**
     * show images for profile
     * 
     * 
     * @param Profile $profile
     * @return $images
     */
    public function showImages(Profile $profile){
        return response()->json([
            'status' => true,
            'message' => 'profile Images for user: '. $profile->user->user_name,
            'data'=>[
                'images' => $profile->profileImages,
            ]
        ], 200);
    }

    function image($image, $type, $profile_id){

        $response = cloudinary()->upload($image->getRealPath())->getSecurePath();

        ProfileImage::create([
            'profile_id'=> $profile_id,
            'path'=> $response,
            'type' => $type,
        ]);
        
        return $response;
    }
}
