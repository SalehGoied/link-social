<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\ProfileImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

/**
 * @group profile image
 *
 * APIs for image of profile
 */
class ProfileImageController extends Controller
{

    /**
     * images for profile
     * 
     * @param Profile $profile
     * @return $profileImages
     */
    public function index(Profile $profile){
        return response()->success(['images' => $profile->profileImages,], 'profile Images for user:'. $profile->user->user_name);
    }

    /**
     * image
     * 
     * @param ProfileImage $profileImage
     * @return $profileImage
     */

    public function show(ProfileImage $profileImage){
        return response()->success(['image' => $profileImage,], 'Image');
    }

    // public function update(Request $request, ProfileImage $profileImage){

    //     $validatefile = Validator::make([$request->image],  [ 'image' => 'mimes:jpeg,jpg,png,gif|max:10000' ]);

    //     if($validatefile->fails()){
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'validation error',
    //             'errors' => $validatefile->errors()
    //         ], 400);
    //     }
    //     $image = $request->image;
    //     $filename = time() . "." . $image->getClientOriginalExtension();
    //     $src = 'uploads/profile/'.$filename;
    //     Image::make($image)->save(public_path($src));
    //     $profileImage->update([
    //         'path'=> $src,
    //     ]);
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Images update successfully',
    //         'data'=>[
    //             'image' => $profileImage,
    //         ]
    //     ], 200);

    // }

    /**
     * delete image
     * 
     * @authenticated
     * @param ProfileImage $profileImage
     * @return 
     */

    public function delete(ProfileImage $profileImage){

        $this->authorize('delete', $profileImage);

        $path = $profileImage->path;
        $profile = $profileImage->profile;
        if($profile->avatar == $path) $profile->avatar = null;
        elseif($profile->cover == $path) $profile->cover = null;
        $profile->save();

        $profileImage->delete();

        return response()->success([], 'Image deleted successfaly');
    }
}
