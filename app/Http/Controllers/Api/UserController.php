<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


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

        $users = (new UserService())->search($request->key);
        
        return response()->success(['users' => $users], 'Users');
    }

    /**
     * show user
     * 
     * @param User $user
     * @return User
     */
    public function show(User $user){
        return response()->success(['user' => $user->load('following', 'profile.followers')], 'Users');
    }


    /**
     * show profile for user
     * 
     * @param User $user
     * @return Profile
     */
    public function profile(User $user){
        return response()->success(['profile' => $user->profile,], 'Profile for User');
    }

    /**
     * show posts for user
     * 
     * @param User $user
     * @return $posts
     */

    public function posts(User $user){
        return response()->success(['posts' => $user->posts,], 'Posts for User: ' .$user->user_name);
    }

    /**
     * update User
     * 
     * @authenticated
     * @param Request $request
     * @return User
     */

    public function update(UpdateUserRequest $request){

        $user = Auth::user();

        if($request->has('current_password')){

            if(! Hash::check( $request->current_password ,$user->password)){
                return response()->error("Password does not match with our record.", 401);
            }

            $request->validate(['new_password' => 'required|confirmed|min:8']);

            User::find($user->id)->update([
                'password' =>Hash::make($request->new_password),
            ]);
        }
        
        User::find($user->id)->update($request->validated());
        
        $user = User::find($user->id);

        return response()->success(['user' => $user,], 'User Updated Successfully');
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
