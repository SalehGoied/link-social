<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;


/**
 * @group Photo
 *
 * APIs for photos
 */
class PhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // foreach(Profile::all() as $profile){
            
        //     $avatar = $profile->photos()->create([
        //         'path'=> 'https://picsum.photos/200',
        //         'type'=> 'avatar',
        //     ]);

        //     $cover = $profile->photos()->create([
        //         'path'=> 'https://picsum.photos/200',
        //         'type'=> 'cover',
        //     ]);

        //     $profile->update([
        //         'description' => fake()->sentence(),
        //         'avatar' => $avatar->id,
        //         'cover' => $cover->id,
        //     ]);
        // }

        foreach(Post::all() as $post){
            
            for($i = 0; $i < range(1, 3); $i++){
                $post->photos()->create([
                    'path'=> 'https://picsum.photos/200',
                    'type'=> 'post',
                ]);
            }
        }

        foreach(Comment::all() as $comment){
            
            for($i = 0; $i < range(0, 3); $i++){
                $comment->photos()->create([
                    'path'=> 'https://picsum.photos/200',
                    'type'=> 'post',
                ]);
            }
        }
    }
}
