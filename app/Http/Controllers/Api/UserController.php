<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function index(Request $request){
        
        return response()->json([
            'status' => true,
            'message' => 'Users',
            'data'=>[
                'users' => User::all(),
            ]
            
        ], 200);
    }

    public function show(Request $request, User $user){
        
        return response()->json([
            'status' => true,
            'message' => 'User',
            'data'=>[
                'user' => $user,
                'followres' => $user->profile->followers,
                'following' => $user->following,
            ],
        ], 200);
    }

    public function profile(Request $request, User $user){
        
        return response()->json([
            'status' => true,
            'message' => 'Profile for User',
            'data'=>[
                'profile' => $user->profile,
            ],
        ], 200);
    }

    public function update(Request $request){

        $user = Auth::user();

        $validateUser = Validator::make($request->all(), 
            [
                'user_name' => 'nullable',
                'password' => 'nullable',
                'first_name'=>'nullable',
                'last_name'=>'nullable',
                'phone'=> 'nullable',
                'age'=>'nullable',
                'gender'=>'nullable',
            ]);

        
        User::find($user->id)->update([
            'user_name' => $request->user_name ? :$user->user_name,
            'first_name'=>$request->first_name? :$user->first_name,
            'last_name'=>$request->last_name?  :$user->last_name,
            'phone'=> $request->phone? :$user->phone,
            'age'=> $request->age? :$user->age,
            'gender'=>$request->gender? :$user->gender,
            'password' => $request->password? Hash::make($request->password):$user->password,
        ]);
        $user = User::find($user->id);


        return response()->json([
            'status' => true,
            'message' => 'User Updated Successfully',
            'data'=>[
                'user' => $user,
            ],
        ], 200);
    }
}
