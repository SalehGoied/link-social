<?php

namespace App\Services;

use App\Models\ProfileImage;

class ProfileService
{
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