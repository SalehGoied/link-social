<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Photo;
use App\Models\ProfileImage;
use Illuminate\Http\Request;

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
            // dd('12323');
            $comment = Comment::find(28)->userdata;
            dd($comment);

            $photo = Photo::with('photoable')->first();

            return $photo;
            if ($request->hasFile('file'))
            {

                $image= $request->file('file');
                // dd($image);
            //     $fileName= time() . '.' . $image->getClientOriginalExtension();

            //     $img = Image::make($image->getRealPath());
            //     $img->resize(120, 120, function ($constraint) {
            //         $constraint->aspectRatio();
            //     });

            //     $img->stream(); // <-- Key point

            // //dd();

                // $response = Cloudinary::upload($image->getRealPath())->getSecurePath();
                $response = cloudinary()->upload($image->getRealPath())->getSecurePath();
                dd($response);
                $image = $request->file;
                $filename = '_'.uniqid(). "." . $image->getClientOriginalExtension();
                $image->storeAs(
                    'public/avatars', $filename
                    );
                // $filename = '_'.uniqid(). "." . $image->getClientOriginalExtension();
                // // $path = '/uploads/profile/'.$filename;
                // $img = Image::make($image->getRealPath());
                // $img->resize(120, 120, function ($constraint) {
                //             $constraint->aspectRatio();
                //         });
                // // Storage::put($image, $path);
                // Storage::disk('local')->put('public/images/'.$filename, $img, 'public');

                // Image::make($image)->save($src);

                return 'storage/avatars/'. $filename;
            }
            return "src";
        }
}
