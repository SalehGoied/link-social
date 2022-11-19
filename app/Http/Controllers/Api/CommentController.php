<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index(Post $post){
        return response()->json([
            'status' => true,
            'message' => 'comments',
            'data'=>[
                'comments' => $post->comments,
            ]
        ], 200);
    }

    public function show(Comment $comment){
        return response()->json([
            'status' => true,
            'message' => 'comment',
            'data'=>[
                'comment' => $comment,
            ]
        ], 200);
    }

    public function store(Request $request, Post $post){
        
        $validateComment = Validator::make($request->all(), [ 'body'=>'required|string']);

        if($validateComment->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateComment->errors()
            ], 400);
        }

        /**
         * @var $user
         */
        $user = auth()->user();
        $comment = $user->comments()->create([
            'post_id'=> $post->id,
            'body'=> $request->body,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'New comment',
            'data'=>[
                'comment' => $comment,
            ]
        ], 200);

    }

    public function update(Request $request, Comment $comment){

        if(! (auth()->id() == $comment->user_id)){
            return response()->json([
                'status' => false,
                'message' => "you can't update this comment",
            ], 403);
        }

        $comment->update(['body'=> $request->body,]);

        return response()->json([
            'status' => true,
            'message' => 'Comment updated successfuly',
            'data'=>[
                'comment' => $comment,
            ]
        ], 200);

    }

    public function delete(Comment $comment){
        if(! (auth()->id() == $comment->user_id )&& ! (auth()->id() == $comment->post->user_id)){
            return response()->json([
                'status' => false,
                'message' => "you can't delete this comment",
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'status' => true,
            'message' => 'Comment deleted successfuly',
        ], 200);
    }
}
