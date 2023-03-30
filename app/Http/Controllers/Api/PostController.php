<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexPostRequest;
use App\Http\Requests\SharePostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Http\Request;



/**
 * @group Post
 *
 * APIs for Posts
 */

class PostController extends Controller
{

    /**
     * posts with files
     *
     * @bodyParam key string The key for search.
     * @bodyParam limit intger The Limit of posts.
     *
     * @param Request $request
     * @return $posts
     */
    public function index(IndexPostRequest $request, PostService $service){

        $posts = $service->search($request->key, $request->limit);

        return response()->success(['posts' =>$posts],'posts');
    }


    /**
     * show posts for user
     * @param User $user
     * @return $posts
     */
    public function showUserPosts(User $user){
        return response()->success(['posts' => $user->posts->load('comments', 'photos', 'user.profile'),],'posts');
    }

    /**
     * show post
     * @param Post $post
     * @return Post
     */
    public function show(Post $post){
        $post = Post::with('photos', 'user.profile', 'parent.user.profile')->withCount('comments', 'reacts')->find($post->id);
        return response()->success(['posts' =>$post],'post');
    }


    /**
     * create post
     *
     * @authenticated
     * @param Request $request
     * @return Post
     */
    public function store(StorePostRequest $request){


        $post = (new PostService())->store($request->validated());

        return response()->success(['post' => $post->load('photos')],'New Post');
    }

    /**
     * update post
     *
     * @authenticated
     * @param Request $request, Post $post
     * @return Post
     */

    public function update(UpdatePostRequest $request, Post $post){

        $post = (new PostService())->update($request->validated(), $post);

        if(! $post->body && ! $post->photos->count() && ! $post->post_id){
            $post->destory();
            return response()->success('Post have No content, Post deleted');
        }

        return response()->success(['post' =>$post->load('photos')],'Update Post');
    }


    /**
     * delete post
     *
     * @authenticated
     * @param Post $post
     * @return ''
     */

    public function delete(Post $post){

        $this->authorize('delete', $post);

        $post->destory();

        return response()->success([], 'Post Deleted successfully');
    }

    /**
     * share post
     *
     * @authenticated
     * @param Post $post
     * @param Request $request
     * @return ''
     */

    public function share(SharePostRequest $request, Post $post){

        $new_post = $request->share($post);

        return response()->success(['post' =>$new_post->load('parent')],'Share Post');
    }

}
