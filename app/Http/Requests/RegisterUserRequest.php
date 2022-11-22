<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_name' => 'required|string|unique:users,user_name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'first_name'=>'required|string',
            'last_name'=>'required|string',
            'phone'=> 'nullable|string',
            'country'=>'nullable|string',
            'status'=>'nullable|string',
            'region'=> 'nullable|string',
            'birthday'=>'nullable|date',
            'gender'=>'nullable|string',
        ];
    }
}
