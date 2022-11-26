<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

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
            'files.*'=> 'nullable|mimes:mp4,mov,ogg,qt,jpeg,jpg,png,gif|max:20000'
        ];
    }
}
