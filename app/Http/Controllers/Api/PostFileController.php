<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


/**
 * @group file post
 *
 * APIs for files of posts
 */


class PostFileController extends Controller
{

    /**
     * files for one post
     * 
     * @param Post $post
     * @return $files
     */

    public function index(Post $post){
        return response()->json([
            'status' => true,
            'message' => 'files',
            'data'=>[
                'files' => $post->files,
            ]
        ], 200);
    }

    /**
     * show one file
     * 
     * @param PostFile $postFile
     * @return PostFile
     */

    public function show(PostFile $postFile){
        return response()->json([
            'status' => true,
            'message' => 'file',
            'data'=>[
                'file' => $postFile,
            ]
            
        ], 200);
    }

    /**
     * delete
     * 
     * @authenticated
     * @param PostFile $postFile
     * @return void
     */

    public function delete(PostFile $postFile){

        if(! (auth()->id() == $postFile->post->user_id)){
            return response()->json([
                    'status' => false,
                    'message' => "you can't delete this file",
                ], 404);
        }

        $post = $postFile->post;

        $postFile->delete();

        $message = "file deleted sucessfully";
        if(! $post->bady&& ! $post->files->count() ){
            $post->destory();
            $message .= ", post has no content";
        }

        return response()->json([
            'status' => true,
            'message' => $message,
            
        ], 200);
    }



}
