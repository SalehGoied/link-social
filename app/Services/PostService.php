<?php

namespace App\Services;

use App\Models\Post;

class PostService
{

    public function search(String $key = null, int $limit = null)
    {
        $posts = Post::with('photos', 'user.profile', 'parent.user.profile')
                ->withCount( 'comments', 'reacts', 'children');

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

        return $posts->latest()->take($limit)->get();
    }

    public function store(array $columns)
    {
        /**
         * @var $user
         */
        $user = auth()->user();
        $post = $user->posts()->create($columns);

        if (isset($columns['photos'])){
            foreach($columns['photos'] as $photo){
                (new PhotoService())->store($post, $photo, 'post');
            }
        }

        return $post;
    }

    public function update(array $columns, Post $post)
    {

        if (isset($columns['photos']) && ! $post->post_id){
            foreach($columns['photos'] as $photo){
                (new PhotoService())->store($post, $photo, 'post');
            }
        }

        $post->update($columns);

        return $post;
    }

}
