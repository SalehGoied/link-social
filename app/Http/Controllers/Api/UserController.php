<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


/**
 * @group User
 *
 * APIs for User
 */
class UserController extends Controller
{

    /**
     * users
     * 
     * @bodyParam key string The key for search.
     * 
     * @param Request $request
     * @return $users
     */
    public function index(Request $request){

        $users = User::with('profile');

        if($request->key){
            $users->where(function($query) use ($request) {
                $query->where('user_name', 'LIKE', '%' . $request->key . '%')
                    ->orWhere('first_name', 'LIKE', '%' . $request->key . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $request->key . '%');
            });
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Users',
            'data'=>[
                'users' => $users->get(),
            ]
            
        ], 200);
    }

    /**
     * show user
     * 
     * @param User $user
     * @return User
     */
    public function show(User $user){
        
        return response()->json([
            'status' => true,
            'message' => 'User',
            'data'=>[
                'user' => $user->load('following', 'profile.followers'),
            ],
        ], 200);
    }


    /**
     * show profile for user
     * 
     * @param User $user
     * @return Profile
     */
    public function profile(User $user){
        
        return response()->json([
            'status' => true,
            'message' => 'Profile for User',
            'data'=>[
                'profile' => $user->profile,
            ],
        ], 200);
    }

    /**
     * show posts for user
     * 
     * @param User $user
     * @return $posts
     */

    public function posts(User $user){
        return response()->json([
            'status' => true,
            'message' => 'Posts for User: ' .$user->user_name,
            'data'=>[
                'posts' => $user->posts,
            ],
        ], 200);
    }

    /**
     * update User
     * 
     * @authenticated
     * @param Request $request
     * @return User
     */

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
                'country'=>'nullable|string',
                'status'=>'nullable|string',
                'region'=> 'nullable|string',
                'birthday'=>'nullable|date',
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
            'birthday'=> $request->birthday? :$user->birthday,
            'country'=> $request->country? :$user->country,
            'status'=> $request->status? :$user->status,
            'region'=> $request->region? :$user->region,
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

    // public function search(Request $request){

    //     $users = User::with('profile');

    //     if($request->key){
    //         $users->where(function($query) use ($request) {
    //             $query->where('user_name', 'LIKE', '%' . $request->key . '%')
    //                 ->orWhere('first_name', 'LIKE', '%' . $request->key . '%')
    //                 ->orWhere('last_name', 'LIKE', '%' . $request->key . '%');
    //         });
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Users related with: '. $request->key??'',
    //         'data'=>[
    //             'user' => $users->get(),
    //         ],
    //     ], 200);
    // }
}
