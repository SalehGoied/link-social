<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return true;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_name' => 'nullable|string|unique:users,user_name,'. auth()->id(),
            'email' => 'nullable|string|unique:users,email,'. auth()->id(),
            'current_password'=> 'nullable|string',
            'new_password' => 'nullable|confirmed|min:8',
            'first_name'=>'nullable|string',
            'last_name'=>'nullable|string',
            'phone'=> 'nullable|string',
            'country'=>'nullable|string',
            'status'=>'nullable|in:married,single,engaged',
            'region'=> 'nullable|string',
            'birthday'=>'nullable|date',
            'gender'=>'nullable|in:male,female',
        ];
    }
}
