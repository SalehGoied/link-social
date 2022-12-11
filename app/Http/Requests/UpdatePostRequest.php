<?php

namespace App\Http\Requests;

use App\Models\Post;
use App\Services\PhotoService;
use App\Services\PostService;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $post = $this->route('post');

        return $post->user_id == auth()->id();
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
            'can_comment' => 'nullable|boolean',
            'can_sharing' => 'nullable|boolean',
            'photos.*'=> 'nullable|mimes:mp4,mov,ogg,qt,jpeg,jpg,png,gif|max:20000'
        ];
    }
}
