<?php

namespace App\Http\Controllers;

use App\Models\ProfileImage;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Stevebauman\Location\Facades\Location;

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

            /* $ip = $request->ip(); Dynamic IP address */
            // $ip = $this->server->get('REMOTE_ADDR');; /* Static IP address */
            // $currentUserInfo = Location::get($ip);
            return $this->getIp(); 

            // if ($request->hasFile('file'))
            // {
            //     $image = $request->file;
            //     $filename = '_'.uniqid(). "." . $image->getClientOriginalExtension();
            //     $src = 'uploads/profile/'.$filename;
            //     Image::make($image)->save(public_path($src));

            //     // ProfileImage::create([
            //     //     'profile_id'=> 1,
            //     //     'path'=> $src,
            //     //     'type' => 'cover',
            //     // ]);
                
            //     return $src;
            // }
        }

        public function getIp(){
            foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
                if (array_key_exists($key, $_SERVER) === true){
                    foreach (explode(',', $_SERVER[$key]) as $ip){
                        $ip = trim($ip); // just to be safe
                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                            return $ip;
                        }
                    }
                }
            }
            return request()->ip(); // it will return server ip when no client ip found
        }
}
