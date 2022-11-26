<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'avatar' => 'nullable|mimes:jpeg,jpg,png,gif|max:10000',
            'cover' => 'nullable|mimes:jpeg,jpg,png,gif|max:10000',
            'description' => 'nullable|string',
        ];
    }
}
