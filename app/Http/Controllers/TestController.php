<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testUploadeImages(Request $request){

        // foreach($request->file('image') as $uploadedFile){
        //     return response()->json([
        //         'req'=> $uploadedFile,
        //     ], 200);
        // }
        return response()->json([
            'req'=> $request->file('image')[1]->getClientOriginalExtension(),
        ], 200);
    }
}
