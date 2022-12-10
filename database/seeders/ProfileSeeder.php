<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
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
                'avatar' => "https://picsum.photos/200",
                'cover' => "https://picsum.photos/200",
            ]);

            $i = rand(0, 10);
            while($i--){
                User::find(rand(1,50))->following()->toggle($profile);
            }
            
            $profile->profileImages()->create([
                'path'=> 'https://picsum.photos/200',
                'type'=> 'avatar',
            ]);

            $profile->profileImages()->create([
                'path'=> 'https://picsum.photos/200',
                'type'=> 'cover',
            ]);
        }
    }
}
