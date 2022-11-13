<?php

namespace App\Http\Controllers;

use App\Models\ProfileImage;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image; 

class TestController extends Controller
{
    // public function testUploadeImages(Request $request){

    //     foreach($request->file('image') as $file){
            
    //             $type = explode("/", $file->getMimeType())[0];
    //             return response()->json([
    //                 'req'=> $type,
    //             ], 200);

    //             if(in_array($file->getMimeType() ,$imagemimes)) {
    //                 $filevalidate = 'required|mimes:jpeg|max:2048';
    //             }
    //             //Validate video
    //             if (in_array($file->getMimeType() ,$videomimes)) {
    //                 $filevalidate = 'required|mimes:mp4';
    //             }
    //             return response()->json([
    //                 'req'=> $filevalidate,
    //             ], 200);
            
    //         return response()->json([
    //             'req'=> $file,
    //         ], 200);
    //     }
    //     return response()->json([
    //         'req'=> $request->file('image')[1]->getClientOriginalExtension(),
    //     ], 200);
    // }

        public function test(Request $request){
            if ($request->hasFile('file'))
            {
                $image = $request->file;
                $filename = '_'.uniqid(). "." . $image->getClientOriginalExtension();
                $src = 'uploads/profile/'.$filename;
                Image::make($image)->save(public_path($src));

                // ProfileImage::create([
                //     'profile_id'=> 1,
                //     'path'=> $src,
                //     'type' => 'cover',
                // ]);
                
                return $src;
            }
        }
}
