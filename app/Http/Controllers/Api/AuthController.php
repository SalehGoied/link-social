<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUSerRequest;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @group Auth
 *
 * APIs for Auth users
 */

class AuthController extends Controller
{
    /**
     * Create User
     *
     * @param Request $request
     * @return User
     */
    public function registerUser(RegisterUserRequest $request)
    {
        try {
            $user = User::create(array_merge($request->validated() ,[
                'password' => Hash::make($request->password)
            ]));

            return response()->success(
                [
                    'user' => $user->load('profile'),
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ],
                'User Created Successfully'
            );

        } catch (\Throwable $th) {
            return response()->error($th->getMessage(),500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(LoginUSerRequest $request)
    {
        try {

            if(! Auth::attempt($request->only(['email', 'password']))){
                return response()->error('Email & Password does not match with our record.',401);
            }

            $user = User::where('email', $request->email)->with('profile')->first();
            return response()->success(
                [
                    'user' => $user->load('profile'),
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ],
                'User Logged In Successfully'
            );

        } catch (\Throwable $th) {
            return response()->error($th->getMessage(),500);
        }
    }


    /**
     * Logout User
     *
     * @authenticated
     * @param Request $request
     * @return ''
     */
    public function logout(Request $request) {

        try {
            $token = $request->user()->currentAccessToken()->delete();

            return response()->success([],'User Logout Successfully');

        }catch(\Throwable $th){
            return response()->error($th->getMessage(),500);
        }

    }
}
