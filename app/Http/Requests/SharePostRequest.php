<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;

class SharePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $post = $this->route('post');
        return $post->can_sharing;;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'body' => 'nullable|string',
            'can_comment' => 'nullable|boolean'
        ];
    }

    public function share(Post $post): Post
    {   
        $post_id = $post->post_id?: $post->id;
        /**
         * @var $user
         */
        $user = auth()->user();

        $new_post = $user->posts()->create($this->all() + ['post_id'=> $post_id]);

        return $new_post;
    }
}
