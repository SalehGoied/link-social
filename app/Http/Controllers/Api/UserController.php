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
                'user_name' => 'nullable|string',
                'current_password'=> 'nullable|string',
                'new_password' => 'nullable|confirmed|min:8',
                'first_name'=>'nullable|string',
                'last_name'=>'nullable|string',
                'phone'=> 'nullable',
                'age'=>'nullable',
                'gender'=>'nullable',
            ]);
        if($request->has('user_name')){
            $validate_user_name = Validator::make($request->all(), 
            [
                'user_name' => 'required|string|unique:users,user_name',
            ]);

            if($validate_user_name->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate_user_name->errors()
                ], 400);
            }

        }

        if($request->has('current_password')){

            if(! Hash::check( $request->current_password ,$user->password)){
                return response()->json([
                    'status' => false,
                    'message' => 'Password does not match with our record.',
                ], 401);
            }

            $validatepass = Validator::make($request->all(), 
            [
                'new_password' => 'required|confirmed|min:8',
            ]);

            if($validatepass->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validatepass->errors()
                ], 401);
            }

            User::find($user->id)->update([
                'password' =>Hash::make($request->new_password),
            ]);
        }
        
        User::find($user->id)->update([
            'user_name' => $request->user_name ? :$user->user_name,
            'first_name'=>$request->first_name? :$user->first_name,
            'last_name'=>$request->last_name?  :$user->last_name,
            'phone'=> $request->phone? :$user->phone,
            'age'=> $request->age? :$user->age,
            'gender'=>$request->gender? :$user->gender,
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
