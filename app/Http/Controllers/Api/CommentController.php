<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


/**
 * @group Comment
 *
 * APIs for comment on post
 */
class CommentController extends Controller
{

    /**
     * comments for one post
     * @param Post $post
     * @return $comments
     */
    public function index(Post $post){
        return response()->json([
            'status' => true,
            'message' => 'comments',
            'data'=>[
                'comments' => $post->comments,
            ]
        ], 200);
    }

    /**
     * show Comment
     * @param Comment $comment
     * @return Comment
     */
    public function show(Comment $comment){
        return response()->json([
            'status' => true,
            'message' => 'comment',
            'data'=>[
                'comment' => $comment,
            ]
        ], 200);
    }


    /**
     * Create Comment
     * 
     * @authenticated
     * @param Request $request, Post $post
     * @return Comment
     */
    public function store(StoreCommentRequest $request, Post $post){

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


    /**
     * Update Comment
     * 
     * @authenticated
     * @param Request $request
     * @param Comment $comment
     * @return Comment
     */
    public function update(UpdateCommentRequest $request, Comment $comment){

        $comment->update(['body'=> $request->body,]);

        return response()->json([
            'status' => true,
            'message' => 'Comment updated successfuly',
            'data'=>[
                'comment' => $comment,
            ]
        ], 200);

    }
    

    /**
     * delete Comment
     * 
     * <aside class="notice">Deleting a comment is by the owner of the comment or post.😕</aside>
     * 
     * @authenticated
     * 
     * @param Comment $comment
     * @return ''
     */
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
