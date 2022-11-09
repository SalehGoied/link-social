<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function index(Request $request){
        return response()->json([
            'status' => true,
            'message' => 'Profiles',
            'profiles' => Profile::all(),
        ], 200);
    }

    public function show(Request $request, Profile $profile){
        return response()->json([
            'status' => true,
            'message' => 'Profile',
            'profile' => $profile,
            'followres' => $profile->followers,
            'following' => $profile->user->following,
        ], 200);
    }

    public function user(Request $request, Profile $profile){
        return response()->json([
            'status' => true,
            'message' => 'User for Profile',
            'user' => $profile->user,
        ], 200);
    }

    public function update(Request $request){

        $profile = auth()->user()->profile;

        if ($request->hasFile('avatar')){
            $avatar = $request->file('avatar');
            $filename = time() . "." . $avatar->getClientOriginalExtension();
            Image::make($avatar)->resize(300,300)->save(public_path('/profile/'.$filename)); 
            $path_avatat = 'profile/'.$filename;
        }
        else{
            $path_avatat = $profile->avatar;
        }

        if ($request->hasFile('cover')){
            $cover = $request->file('cover');
            $filename = time() . "." . $cover->getClientOriginalExtension();
            Image::make($cover)->save(public_path('/uploads/profile/'.$filename)); 
            $path_cover = 'profile/'.$filename;
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
}
