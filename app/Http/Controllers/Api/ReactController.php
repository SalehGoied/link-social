<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReactRequest;
use App\Http\Requests\UpdateReactRequest;
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

    public function react(StoreReactRequest $request, Post $post){
        
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

    public function update(UpdateReactRequest $request, React $react){

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
