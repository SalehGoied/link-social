<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class PostController extends Controller
{
    public function index(Request $request){

        return response()->json([
            'status' => true,
            'message' => 'posts',
            'data'=>[
                'posts' => Post::with('files')->get(),
            ]
            
        ], 200);
    }

    public function showUserPosts(User $user){
        return response()->json([
            'status' => true,
            'message' => 'post',
            'data'=>[
                'posts' => $user->posts->load('comments', 'files'),
            ]
            
        ], 200);
    }

    public function show(Request $request, Post $post){

        return response()->json([
            'status' => true,
            'message' => 'post',
            'data'=>[
                'posts' => $post->load('comments', 'files'),
            ]
            
        ], 200);
    }

    public function store(Request $request){
        /**
         * @var $user
         */
        $user = auth()->user();
        $post = $user->posts()->create();

        if ($request->hasFile('files')){
            foreach($request->file('files') as $file){

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
                    return response()->json([
                        'status' => false,
                        'message' => 'validation error',
                        'errors' => $validatefile->errors()
                    ], 400);
                }

                $path = $this->file($file, $type, $post->id);
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

    public function update(Request $request, Post $post){

    }

    public function delete(Post $post){

        if(! auth()->id() == $post->user_id){
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
    function file($file, $type, $post_id){
        
        $filename = $type.'_'.uniqid(). "." . $file->getClientOriginalExtension();
        $path = public_path().'/uploads/posts/';
        $file->move($path, $filename);

        PostFile::create([
            'post_id'=> $post_id,
            'path'=> "uploads/posts/". $filename,
            'type' => $type,
        ]);
        
        return "uploads/posts/". $filename;
    }
}
