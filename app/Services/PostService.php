<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostFile;

class PostService
{

    public function search(String $key = null)
    {
        $posts = Post::with('files');

        if($key){
            $posts->where('body', 'LIKE', '%' . $key. '%');
        }
        elseif(auth('sanctum')->user()){
            /**
         * @var $user
         */
            $user = auth('sanctum')->user();
            $users = $user->following()->pluck('profiles.user_id');

            $posts->whereIn('user_id', $users)->latest();
        }

        return $posts->get();
    }


    public function storeFiles($post_id, $files){

        foreach($files as $file){            
            $path = cloudinary()->upload($file->getRealPath())->getSecurePath();

            PostFile::create([
                'post_id'=> $post_id,
                'path'=> $path,
                'type' => 'post',
            ]);
        }
    }

}