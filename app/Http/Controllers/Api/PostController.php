<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        return response()->json([
            'status' => true,
            'message' => 'posts',
            'data'=>[
                'posts' => $posts->get(),
            ]
            
        ], 200);
    }


    /**
     * show posts for user
     * @param User $user
     * @return $posts
     */
    public function showUserPosts(User $user){
        return response()->json([
            'status' => true,
            'message' => 'post',
            'data'=>[
                'posts' => $user->posts->load('comments', 'files'),
            ]
            
        ], 200);
    }

    /**
     * show post
     * @param Post $post
     * @return Post
     */
    public function show(Post $post){

        return response()->json([
            'status' => true,
            'message' => 'post',
            'data'=>[
                'posts' => $post->load('comments', 'files'),
            ]
            
        ], 200);
    }


    /**
     * create post
     * 
     * @authenticated
     * @param Request $request
     * @return Post
     */
    public function store(Request $request){
        /**
         * @var $user
         */
        $user = auth()->user();
        $post = $user->posts()->create();

        if ($request->hasFile('files')){
            
            $validatefile = $this->storeFile($post ,$request->file('files'));

            if($validatefile['status'] == false){
                $post->destory();
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validatefile['errors'],
                ], 400);
            }
        }

        $post->update([
            'body' => $request->body?? null,
        ]);
        
        
        if(! $post->body && ! $post->files->count()){
            $post->destory();
            return response()->json([
                'status' => false,
                'message' => 'no content',
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'New Post',
            'data' => [
                'post' => $post->load('files'),
            ]
        ], 200);

    }

    /**
     * update post
     * 
     * @authenticated
     * @param Request $request, Post $post
     * @return Post
     */

    public function update(Request $request, Post $post){

        if(! (auth()->id() == $post->user_id)){
            return response()->json([
                    'status' => false,
                    'message' => "you can't update this post",
                ], 403);
        }

        if ($request->hasFile('files')){
            
            $validatefile = $this->storeFile($post ,$request->file('files'));
            if($validatefile['status'] == false){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validatefile['errors']
                ], 400);
            }
        }

        $post->update([
            'body' => $request->body?? $post->body,
        ]);
        
        
        if(! $post->body && ! $post->files->count()){
            $post->destory();
            return response()->json([
                'status' => false,
                'message' => 'no content',
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'update Post',
            'data' => [
                'post' => $post->load('files'),
            ]
        ], 200);

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
            return response()->json([
                    'status' => false,
                    'message' => "you can't delete this post",
                ], 404);
        }
        $post->destory();

        return response()->json([
            'status' => true,
            'message' => 'Post Deleted successfully',
        ], 200);
    }



    // helper function

    function storeFile($post, $files){
        foreach($files as $file){

            $type = explode("/", $file->getMimeType())[0];
            //Validate video
            if($type == "video") {
                $filevalidate = 'mimes:mp4,mov,ogg,qt|max:20000';
            }
            //Validate image
            elseif ($type == "image") {
                $filevalidate = 'mimes:jpeg,jpg,png,gif|max:10000';
            }

            $validatefile = Validator::make([$file],  [ 'file' => $filevalidate ]);

            if($validatefile->fails()){
                $post->destory();
                return ['status'=> false, 'error'=> $validatefile->errors()];
            }

            /**
             * @ignore cloudinary
             */

            $response = cloudinary()->upload($file->getRealPath())->getSecurePath();

            PostFile::create([
                'post_id'=> $post->id,
                'path'=> $response,
                'type' => $type,
            ]);

            // $path = $this->file($file, $type, $post->id);
        }
        return ['status'=> true];
    }


    // function file($file, $type, $post_id){
        
    //     $filename = $type.'_'.uniqid(). "." . $file->getClientOriginalExtension();
    //     $path = public_path().'/uploads/posts/';
    //     $file->move($path, $filename);

    //     PostFile::create([
    //         'post_id'=> $post_id,
    //         'path'=> "uploads/posts/". $filename,
    //         'type' => $type,
    //     ]);
        
    //     return "uploads/posts/". $filename;
    // }
}
