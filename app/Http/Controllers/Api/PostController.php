<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\PostFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


/**
 * @group Post
 *
 * APIs for Posts
 */

class PostController extends Controller
{

    /**
     * posts with files
     * 
     * @bodyParam key string The key for search.
     * 
     * @param Request $request
     * @return $posts
     */
    public function index(Request $request){

        $posts = Post::with('files');
        if($request->key){
            $posts->where('body', 'LIKE', '%' . $request->key . '%');
        }
        elseif(auth('sanctum')->user()){
            /**
         * @var $user
         */
            $user = auth('sanctum')->user();
            $users = $user->following()->pluck('profiles.user_id');

            $posts->whereIn('user_id', $users)->with('files')->latest();
        }


        return response()->success($posts->get(),'posts');
    }


    /**
     * show posts for user
     * @param User $user
     * @return $posts
     */
    public function showUserPosts(User $user){

        return response()->success($user->posts->load('comments', 'files'),'posts');
    }

    /**
     * show post
     * @param Post $post
     * @return Post
     */
    public function show(Post $post){

        return response()->success($post->load('comments', 'files', 'parent'),'post');
    }


    /**
     * create post
     * 
     * @authenticated
     * @param Request $request
     * @return Post
     */
    public function store(PostRequest $request){
        /**
         * @var $user
         */
        $user = auth()->user();
        $post = $user->posts()->create();

        if ($request->hasFile('files')){
            $this->storeFile($post ,$request->file('files'));
        }

        $post->update($request->all());
        
        
        if(! $post->body && ! $post->files->count()){
            $post->destory();
            return response()->error('no content',400);
        }

        return response()->success($post->load('files'),'New Post');
    }

    /**
     * update post
     * 
     * @authenticated
     * @param Request $request, Post $post
     * @return Post
     */

    public function update(PostRequest $request, Post $post){

        if(! (auth()->id() == $post->user_id)){
            return response()->error("you can't update this post", 403);
        }

        if ($request->hasFile('files') && ! $post->post_id){
            $this->storeFile($post ,$request->file('files'));
        }

        $post->update([
            'body' => $request->body?? $post->body,
            'can_comment' => isset($request->can_comment)?$request->can_comment:$post->can_comment,
            'can_sharing' => isset($request->can_sharing)?$request->can_sharing:$post->can_comment,
        ]);
        
        
        if(! $post->body && ! $post->files->count()){
            $post->destory();

            return response()->error('No content',400);
        }

        return response()->success($post->load('files'),'Update Post');

    }


    /**
     * delete post
     * 
     * @authenticated
     * @param Post $post
     * @return ''
     */

    public function delete(Post $post){

        if(! (auth()->id() == $post->user_id)){
            return response()->error("you can't delete this post", 403);
        }
        $post->destory();

        return response()->success([], 'Post Deleted successfully');
    }

    /**
     * share post
     * 
     * @authenticated
     * @param Post $post
     * @param Request $request
     * @return ''
     */

    public function share(Request $request, Post $post){

        if(! $post->can_sharing){
            return response()->error("Cannot share this post", 403);
        }

        $request->validate(['body' => 'nullable|string', 'can_comment' => 'nullable|boolean']);
        
        $post_id = $post->post_id?: $post->id;

        /**
         * @var $user
         */
        $user = auth()->user();

        $new_post = $user->posts()->create([
            'body' => $request->body,
            'post_id' => $post_id,
            'can_comment'=> $request->can_comment??1,
        ]);

        return response()->success($new_post->load('parent'),'Share Post');

    }



    // helper function

    function storeFile($post, $files){

        foreach($files as $file){
            $type = explode("/", $file->getMimeType())[0];

            $path= cloudinary()->upload($file->getRealPath())->getSecurePath();

            PostFile::create([
                'post_id'=> $post->id,
                'path'=> $path,
                'type' => $type,
            ]);

        }
    }

}
