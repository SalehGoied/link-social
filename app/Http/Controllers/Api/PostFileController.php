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
        return response()->success(['files' => $post->files,],'Files');
    }

    /**
     * show one file
     * 
     * @param PostFile $postFile
     * @return PostFile
     */

    public function show(PostFile $postFile){
        return response()->success(['file' => $postFile,],'File');
    }

    /**
     * delete
     * 
     * @authenticated
     * @param PostFile $postFile
     * @return void
     */

    public function delete(PostFile $postFile){

        $this->authorize('delete', $postFile);

        $post = $postFile->post;

        $postFile->delete();

        $message = "File deleted sucessfully";
        if(! $post->bady&& ! $post->files->count() ){
            $post->destory();
            $message .= ", post has no content";
        }
        return response()->success([], $message);
    }



}
