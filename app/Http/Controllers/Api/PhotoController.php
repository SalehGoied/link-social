<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Photo;
use App\Models\Post;
use App\Models\Profile;

/**
 * @group Photo
 *
 * APIs for photo
 */
class PhotoController extends Controller
{
    /**
     * Photos for profile
     * 
     * @param Profile $profile
     * @return $photos
     */

    // public function profilePhotos(Profile $profile){
    //     return response()->success(['photos' => $profile->photos,],'Photos');
    // }

    /**
     * Photos for post
     * 
     * @param Post $post
     * @return $photos
     */

    public function postPhotos(Post $post){
        return response()->success(['photos' => $post->photos,],'Photos');
    }

    /**
     * Photos for comment
     * 
     * @param Comment $comment
     * @return $photos
     */

    public function commentPhotos(Comment $comment){
        return response()->success(['photos' => $comment->photos,],'Photos');
    }

    /**
     * show one file
     * 
     * @param Photo $photo
     * @return Photo
     */

    public function show(Photo $photo){
        return response()->success(['photo' => $photo,],'Photo');
    }

    /**
     * delete photo
     * 
     * @authenticated
     * @param Photo $photo
     * @return []
     */

    public function delete(Photo $photo){

        $this->authorize('delete', $photo);

        $model = $photo->photoable;

        $photo->delete();

        $message = "Photo deleted sucessfully";

        return response()->success($model, $message);
    }
}
