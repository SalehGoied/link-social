<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostFile>
 */
class PostFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'post_id'=> random_int(1, 50),
            'path' => 'uploads/posts/post_'.random_int(1, 25).'.jpg',
            'type' => 'image',
        ];
    }
}
