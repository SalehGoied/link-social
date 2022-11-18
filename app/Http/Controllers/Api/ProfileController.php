<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\ProfileImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function index(Request $request){
        return response()->json([
            'status' => true,
            'message' => 'Profiles',
            'data'=>[
                'profiles' => Profile::all(),
            ],
        ], 200);
    }

    public function show(Request $request, Profile $profile){
        return response()->json([
            'status' => true,
            'message' => 'Profile',
            'data'=> [
                'profile' => $profile,
                'followres' => $profile->followers,
                'following' => $profile->user->following,
            ],
        ], 200);
    }

    public function showUser(Request $request, Profile $profile){
        return response()->json([
            'status' => true,
            'message' => 'User for Profile',
            'data'=>[
                'user' => $profile->user,
            ],
        ], 200);
    }

    public function update(Request $request){

        $profile = auth()->user()->profile;

        if ($request->hasFile('avatar')){
            $validatefile = Validator::make([$request->avatar],  [ 'avatar' => 'mimes:jpeg,jpg,png,gif|max:10000' ]);

            if($validatefile->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validatefile->errors()
                ], 400);
            }
            
            $path_avatat = $this->image($request->file('avatar'), 'profile', 'avatar', $profile->id);
        }
        else{
            $path_avatat = $profile->avatar;
        }

        if ($request->hasFile('cover')){
            $validatefile = Validator::make([$request->cover],  [ 'cover' => 'mimes:jpeg,jpg,png,gif|max:10000' ]);

            if($validatefile->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validatefile->errors()
                ], 400);
            }

            

            $path_cover = $this->image($request->file('cover'), 'profile', 'cover', $profile->id);
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

    public function showImages(Profile $profile){
        return response()->json([
            'status' => true,
            'message' => 'profile Images for user: '. $profile->user->user_name,
            'data'=>[
                'images' => $profile->profileImages,
            ]
        ], 200);
    }

    function image($image, $path, $type, $profile_id){
        // $filename = $type.'_'.uniqid(). "." . $image->getClientOriginalExtension();
        // $src = 'uploads/'.$path.'/'.$filename;
        // Image::make($image)->save(public_path($src));

        $response = cloudinary()->upload($image->getRealPath())->getSecurePath();

        ProfileImage::create([
            'profile_id'=> $profile_id,
            'path'=> $response,
            'type' => $type,
        ]);
        
        return $response;
    }
}
