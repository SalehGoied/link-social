<?php

namespace App\Services;

use App\Models\Comment;

class CommentService
{
    public function store(array $columns, int $post_id){
        /**
         * @var $user
         */
        $user = auth()->user();
        $comment = $user->comments()->create($columns + ['post_id'=> $post_id,]);

        if(isset($columns['photos']))
            foreach($columns['photos'] as $photo){
                (new PhotoService())->store($comment, $photo, 'post');
            }

        return $comment;
    }


    public function update(array $columns, Comment $comment){
        
        $comment->update($columns);

        if(isset($columns['photos']))
            foreach($columns['photos'] as $photo){
                (new PhotoService())->store($comment, $photo, 'post');
            }

        return $comment;
    }
}