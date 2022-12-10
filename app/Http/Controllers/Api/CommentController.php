<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Http\Request;

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

        return response()->success(['comments' => $post->comments],'Comments');
    }

    /**
     * show Comment
     * @param Comment $comment
     * @return Comment
     */
    public function show(Comment $comment){
        return response()->success(['comment' => $comment],'Comment');
    }


    /**
     * Create Comment
     * 
     * @authenticated
     * @param StoreCommentRequest $request, Post $post
     * @return Comment
     */
    public function store(StoreCommentRequest $request, Post $post){

        $comment = (new CommentService())->store($request->validated(), $post->id);

        return response()->success(['comment' => $comment->load('photos')],'New comment');
    }


    /**
     * Update Comment
     * 
     * @authenticated
     * @param UpdateCommentRequest $request
     * @param Comment $comment
     * @return Comment
     */
    public function update(UpdateCommentRequest $request, Comment $comment){

        $comment = (new CommentService())->update($request->validated(), $comment);

        return response()->success(['comment' => $comment],'Comment updated successfuly');

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

        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->success([],'Comment deleted successfuly');
    }
}
