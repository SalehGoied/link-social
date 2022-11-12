<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request){

        return response()->json([
            'status' => true,
            'message' => 'posts',
            'data'=>[
                'posts' => Post::all(),
            ]
            
        ], 200);
    }

    public function show(Request $request, Post $post){

        return response()->json([
            'status' => true,
            'message' => 'posts',
            'data'=>[
                'posts' => $post->load('comments', 'files'),
            ]
            
        ], 200);
    }

    public function store(Request $request){

    }

    public function update(Request $request, Post $post){

    }

    public function delete(Post $post){
        
    }
}
