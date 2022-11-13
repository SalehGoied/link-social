<?php

namespace Database\Seeders;

use App\Models\Profile;
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
                'description' => fake()->text(),
                'avatar' => "uploads/profile/ava_". rand(1, 17),
                'cover' => "uploads/profile/cov_". rand(1, 15),
            ]);
        }
    }
}
