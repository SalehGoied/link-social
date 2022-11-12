<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testUploadeImages(Request $request){

        foreach($request->file('image') as $file){
            
                $type = explode("/", $file->getMimeType())[0];
                return response()->json([
                    'req'=> $type,
                ], 200);

                if(in_array($file->getMimeType() ,$imagemimes)) {
                    $filevalidate = 'required|mimes:jpeg|max:2048';
                }
                //Validate video
                if (in_array($file->getMimeType() ,$videomimes)) {
                    $filevalidate = 'required|mimes:mp4';
                }
                return response()->json([
                    'req'=> $filevalidate,
                ], 200);
            
            return response()->json([
                'req'=> $file,
            ], 200);
        }
        return response()->json([
            'req'=> $request->file('image')[1]->getClientOriginalExtension(),
        ], 200);
    }
}
