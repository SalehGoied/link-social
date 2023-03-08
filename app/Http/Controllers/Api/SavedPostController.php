<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\SavedPost;
use Illuminate\Http\Request;


/**
 * @group saved post
 *
 * APIs for save and unsave post and show it
 */
class SavedPostController extends Controller
{

    /**
     * show save post
     *
     * @authenticated
     * @return $saved_posts
     */
    public function index(){
        return response()->success(['saved_posts'=> auth()->user()->saved_posts,], 'Saved posts');
    }


    /**
     * Toggle save post
     *
     * @authenticated
     *
     * @param Post $post
     * @return $saved_posts
     */

    public function store(Post $post){

        /**
        * @var $user
        */

        $user = auth()->user();
        $saved_post = $user->saved_posts->where('post_id', $post->id)->first();
        if(! $saved_post){
            $user->saved_posts()->create(['post_id'=>$post->id]);
        }
        else{
            $saved_post->delete();
        }

        $saved_posts = SavedPost::where('user_id', $user->id)->latest()->get();

        return response()->success(['saved_posts'=> $saved_posts], 'Saved posts');
    }
}
