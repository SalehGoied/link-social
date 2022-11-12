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

class ProfileImageController extends Controller
{
    public function index(Profile $profile){
        return response()->json([
            'status' => true,
            'message' => 'profile Images for user:'. $profile->user->user_name,
            'data'=>[
                'images' => $profile->load('profileImages'),
            ]
        ], 200);
    }

    public function show(ProfileImage $profileImage){
        return response()->json([
            'status' => true,
            'message' => 'Image',
            'data'=>[
                'image' => $profileImage,
            ]
        ], 200);
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

    public function delete(ProfileImage $profileImage){

        if(! (auth()->id() == $profileImage->profile->user_id)){
            return response()->json([
                    'status' => false,
                    'message' => "you can't delete this image",
                ], 404);
        }

        $path = $profileImage->path;
        $profile = $profileImage->profile;
        if($profile->avatar == $path) $profile->avatar = null;
        elseif($profile->cover == $path) $profile->cover = null;
        $profile->save();

        $profileImage->delete();
        if(File::exists($path)){
            File::delete($path);
        }

        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfaly ',
        ], 200);
    }
}
