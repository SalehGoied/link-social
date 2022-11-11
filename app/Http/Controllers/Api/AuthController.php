<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function registerUser(Request $request)
    {
        //Validated
        $validateUser = Validator::make($request->all(), 
        [
            'user_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'first_name'=>'required|string',
            'last_name'=>'required|string',
            'phone'=> 'nullable|string',
            'age'=>'nullable|int',
            'gender'=>'nullable|string',
        ]);

        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }
        try {
            

            $user = User::create([
                'user_name' => $request->user_name,
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'phone'=> $request->phone,
                'age'=>$request->age,
                'gender'=>$request->gender,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'data'=>[
                    'user' => $user,
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ],
                
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        $validateUser = Validator::make($request->all(), 
        [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }
        try {

            if(! Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->with('profile')->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'data'=>[
                    'user' => $user,
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ]
                
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request) {
        // $user = auth()->user()->tokens();
        // auth()->user()->tokens()->delete();
        try {
            $token = $request->user()->currentAccessToken();

            $token->delete();
    
            return response()->json([
                'status' => true,
                "message" => 'User Logout Successfully',
            ],200);
                
        }catch(\Throwable $th){

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 401);
        }
        
        
    }
}