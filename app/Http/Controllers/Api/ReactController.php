<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReactRequest;
use App\Http\Requests\UpdateReactRequest;
use App\Models\Post;
use App\Models\React;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @group React
 *
 * APIs for react
 */

class ReactController extends Controller
{

    /**
     * reacts
     * 
     * @param Post $post
     * @return $reacts
     */
    public function index(Post $post){
        return response()->success(['reacts' => $post->reacts,], 'Reacts');
    }

    /**
     * react
     * 
     * @param React $react
     * @return $react
     */

    public function show(React $react){
        return response()->success(['react' => $react,], 'React');
    }


    /**
     * store and unstore react
     * 
     * @authenticated
     * @param Post $post
     * @param StoreReactRequest $request
     * @return $reacts
     */
    public function react(StoreReactRequest $request, Post $post){
        
        /**
         * @var $user
         */
        $user = auth()->user();
        $react = React::where('user_id', $user->id)->where('post_id', $post->id)->first();
        if($react){
            $react->delete();
        }
        else{
            $react = $user->reacts()->create([
                'post_id'=> $post->id,
                'type'=> $request->type?? 1,
            ]);
        }
        return response()->success(['reacts' => $post->reacts,], 'New react');
    }


    /**
     * update react
     * 
     * @authenticated
     * @param React $react
     * @param UpdateReactRequest $request
     * @return $react
     */
    public function update(UpdateReactRequest $request, React $react){

        $react->update([
            'type'=> $request->type,
        ]);

        return response()->success(['react' => $react,], 'React updated successfully');
    }

    // public function delete(React $react){
    //     if(! (auth()->id() == $react->user_id)){
    //         return response()->json([
    //             'status' => false,
    //             'message' => "you can't update this",
    //         ], 404);
    //     }

    //     $react->delete();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'React deleted successfuly',
    //     ], 200);

    // }
}
