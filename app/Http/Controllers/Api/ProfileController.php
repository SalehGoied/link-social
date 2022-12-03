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
    public function index(){
        return response()->success(['profiles' => Profile::all(),], 'Profiles');
    }

    /**
     * show profile
     * 
     * @param Profile $profile
     * @return Profile
     */
    public function show(Request $request, Profile $profile){
        return response()->success(['profile' => $profile->load('followers', 'user.following'),], 'Profile');
    }

    /**
     * show user for profile
     * 
     * @param Profile $profile
     * @return User
     */

    public function showUser(Request $request, Profile $profile){
        return response()->success(['user' => $profile->user,], 'User for Profile');
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

        return response()->success(['profile' => $profile,], 'Update Profile');
    }


    /**
     * show images for profile
     * 
     * 
     * @param Profile $profile
     * @return $images
     */
    public function showImages(Profile $profile){
        return response()->success(['images' => $profile->profileImages,], 'profile Images for user: '. $profile->user->user_name);
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
