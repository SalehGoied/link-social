<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(Profile::all() as $profile){
            $profile->update([
                'description' => fake()->sentence(),
                'avatar' => "https://source.unsplash.com/random",
                'cover' => "https://source.unsplash.com/random",
            ]);

            User::find(rand(1,50))->following()->toggle($profile);
        }
    }
}
