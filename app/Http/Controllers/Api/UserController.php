<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function update(Request $request, User $user){

        if($user->id != auth()->user()->id){
            return response()->json([
                'status' => false,
                'message' => 'error',
                'errors' => 'wrong user', 
            ], 401);
        }
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

        
        $user->update([
            'user_name' => $request->user_name?? $user->user_name,
            'first_name'=>$request->first_name?? $user->first_name,
            'last_name'=>$request->last_name??  $user->last_name,
            'phone'=> $request->phone??  $user->phone,
            'age'=>$request->age?? $user->age,
            'gender'=>$request->gender?? $user->gender,
            'password' => $request->password? Hash::make($request->password):$user->password,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User Updated Successfully',
            'user' => $user,
        ], 200);
    }
}
