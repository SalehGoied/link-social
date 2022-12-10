<?php

namespace App\Services;

class PhotoService
{
    public function store(Object $model, Object $photo, $type = 'photo')
    {

        $path = cloudinary()->upload($photo->getRealPath())->getSecurePath();

        $photo = $model->photos()->create([
            'path'=> $path,
            'type'=> $type,
        ]);

        return $photo;
    }
}