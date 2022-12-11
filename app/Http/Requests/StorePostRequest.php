<?php

namespace App\Http\Requests;

use App\Services\PhotoService;
use App\Services\PostService;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'body' => 'required_without:photos|string',
            'can_comment' => 'nullable|boolean',
            'can_sharing' => 'nullable|boolean',
            'photos.*'=> 'required_without:body|mimes:jpeg,jpg,png,gif|max:10000'
        ];
    }

    
}
