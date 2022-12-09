<?php

namespace App\Services;

use App\Models\ProfileImage;

class ProfileService
{

    public function update(array $coulmns = []){

        $profile = auth()->user()->profile;
        
        isset($coulmns['avatar'])?
            $path_avatat = $this->storeImage($coulmns['avatar'], 'avatar', $profile->id)
            : $path_avatat = $profile->avatar;


        isset($coulmns['cover'])?
            $path_cover = $this->storeImage($coulmns['cover'], 'cover', $profile->id)
            : $path_cover = $profile->avatar;

        $profile->update([
            'description' => isset($coulmns['description']) ? $coulmns['description']: $profile->description,
            'avatar' => $path_avatat,
            'cover' => $path_cover,
        ]);

        return $profile;
    }


    public function storeImage($image, $type, $profile_id){

        $path = cloudinary()->upload($image->getRealPath())->getSecurePath();

        ProfileImage::create([
            'profile_id'=> $profile_id,
            'path'=> $path,
            'type' => $type,
        ]);
        
        return $path;
    }
}