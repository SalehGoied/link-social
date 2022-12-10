<?php

namespace App\Services;

use App\Models\ProfileImage;

class ProfileService
{

    public function update(array $columns = []){

        $profile = auth()->user()->profile;
        
        if(isset($columns['avatar']))
            $columns['avatar'] = $this->storeImage($columns['avatar'], 'avatar', $profile->id);


        if(isset($columns['cover']))
            $columns['cover'] = $this->storeImage($columns['cover'], 'cover', $profile->id);

        $profile->update($columns);

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