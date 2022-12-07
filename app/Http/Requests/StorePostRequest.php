<?php

namespace App\Http\Requests;

use App\Services\PostService;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     $post = $this->route('post');

    //     return $post->user_id == auth()->id();
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'body' => 'required_without:files|string',
            'can_comment' => 'nullable|boolean',
            'can_sharing' => 'nullable|boolean',
            'files.*'=> 'required_without:body|mimes:mp4,mov,ogg,qt,jpeg,jpg,png,gif|max:20000'
        ];
    }

    public function store()
    {
        /**
         * @var $user
         */
        $user = auth()->user();
        $post = $user->posts()->create($this->all());

        if ($this->hasFile('files')){
            (new PostService())->storeFiles($post->id ,$this->file('files'));
        }

        return $post;
    }
}
