<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use App\Services\ProfileService;
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
    public function show(Profile $profile){
        return response()->success(['profile' => $profile->load('followers', 'user.following'),], 'Profile');
    }

    /**
     * show user for profile
     * 
     * @param Profile $profile
     * @return User
     */

    public function showUser(Profile $profile){
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
    public function update(UpdateProfileRequest $request, ProfileService $service){

        $profile = $service->update($request->all());

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
        return response()->success(
            ['images' => $profile->profileImages,], 
            'profile Images for user: '. $profile->user->user_name
        );
    }

    // function image($image, $type, $profile_id){

    //     $response = cloudinary()->upload($image->getRealPath())->getSecurePath();

    //     ProfileImage::create([
    //         'profile_id'=> $profile_id,
    //         'path'=> $response,
    //         'type' => $type,
    //     ]);
        
    //     return $response;
    // }
}
