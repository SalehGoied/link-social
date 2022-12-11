<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReactRequest;
use App\Http\Requests\UpdateReactRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\React;
use App\Services\ReactService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @group React
 *
 * APIs for react
 */

class ReactController extends Controller
{

    /**
     * reacts for post
     * 
     * @param Post $post
     * @return $reacts
     */
    public function postReacts(Post $post){
        return response()->success(['reacts' => $post->reacts,], 'Reacts');
    }

    /**
     * reacts for comment
     * 
     * @param Comment $comment
     * @return $reacts
     */
    public function commentReacts(Comment $comment){
        return response()->success(['reacts' => $comment->reacts,], 'Reacts');
    }

    /**
     * react
     * 
     * @param React $react
     * @return $react
     */

    public function show(React $react){
        return response()->success(['react' => $react,], 'React');
    }


    /**
     * store and unstore react for post
     * 
     * @authenticated
     * @param Post $post
     * @param StoreReactRequest $request
     * @return $reacts
     */
    public function reactPost(StoreReactRequest $request, Post $post){
        
        $reacts = (new ReactService())->store($post, $request->validated());

        return response()->success(['reacts' => $reacts,], 'New react');
    }

    /**
     * store and unstore react for Comment
     * 
     * @authenticated
     * @param Comment $comment
     * @param StoreReactRequest $request
     * @return $reacts
     */
    public function reactComment(StoreReactRequest $request, Comment $comment){
        
        $reacts = (new ReactService())->store($comment, $request->validated());

        return response()->success(['reacts' => $reacts,], 'New react');
    }


    /**
     * update react
     * 
     * @authenticated
     * @param React $react
     * @param UpdateReactRequest $request
     * @return $react
     */
    public function update(UpdateReactRequest $request, React $react){

        $react->update([
            'type'=> $request->type,
        ]);

        return response()->success(['react' => $react,], 'React updated successfully');
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
