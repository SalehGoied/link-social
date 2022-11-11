<?php

namespace App;

use App\Models\ProfileImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;


class Helper
{
    public function storeImage($image, $path, $type){
        $filename = time() . "." . $image->getClientOriginalExtension();
        $src = 'uploads/'.$path.'/'.$filename;
        Image::make($image)->save(public_path($src));

        ProfileImage::create([
            'user_id'=> auth()->id(),
            'path'=> $src,
            'type' => $type,
        ]);
        return $src;
    }

    public function validate($validate){
        return response()->json([
            'status' => false,
            'message' => 'validation error',
            'errors' => $validate->errors()
        ], 400);
    }
}
