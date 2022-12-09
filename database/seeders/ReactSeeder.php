<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(Post::all() as $post){
            $rang = rand(0, 15);
            for($i = 0; $i <= $rang; $i++){
                $post->reacts()->create([
                    'user_id'=> random_int(1, 50),
                    'type'=> random_int(0, 1) || random_int(0, 1)? 'Like': 'DisLike',
                ]);
            }
        }

        foreach(Comment::all() as $comment){
            $rang = rand(0, 10);
            for($i = 0; $i <= $rang; $i++){
                $comment->reacts()->create([
                    'user_id'=> random_int(1, 50),
                    'type'=> random_int(0, 1) || random_int(0, 1)? 'Like': 'DisLike',
                ]);
            }
        }
    }
}
