<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\React;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReactController extends Controller
{
    public function index(Post $post){
        return response()->json([
            'status' => true,
            'message' => 'Reacts',
            'data'=>[
                'reacts' => $post->reacts,
            ]
        ], 200);
    }

    public function show(React $react){
        return response()->json([
            'status' => true,
            'message' => 'React',
            'data'=>[
                'react' => $react,
            ]
        ], 200);
    }

    public function react(Request $request, Post $post){

        $validateReact = Validator::make($request->all(), [ 'type'=>'nullable|integer|between:1,5']);
        if($validateReact->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateReact->errors()
            ], 400);
        }
        /**
         * @var $user
         */
        $user = auth()->user();
        $react = React::where('user_id', $user->id)->where('post_id', $post->id)->first();
        if($react){
            $react->delete();
        }
        else{
            $react = $user->reacts()->create([
                'post_id'=> $post->id,
                'type'=> $request->type?? 1,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'New react',
            'data'=>[
                'reacts' => $post->reacts,
            ]
        ], 200);
    }

    public function update(Request $request, React $react){
        if(! (auth()->id() == $react->user_id)){
            return response()->json([
                'status' => false,
                'message' => "you can't update this",
            ], 404);
        }

        $validateReact = Validator::make($request->all(), [ 'type'=>'required|integer|between:1,5']);

        if($validateReact->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateReact->errors()
            ], 400);
        }

        $react->update([
            'type'=> $request->type,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'React updated successfully',
            'data'=>[
                'react' => $react,
            ]
        ], 200);
    }

    // public function delete(React $react){
    //     if(! (auth()->id() == $react->user_id)){
    //         return response()->json([
    //             'status' => false,
    //             'message' => "you can't update this",
    //         ], 404);
    //     }

    //     $react->delete();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'React deleted successfuly',
    //     ], 200);

    // }
}
