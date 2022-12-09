<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function search(String $key = null)
    {
        $users = User::with('profile');

        if($key){
            $users->where(function($query) use ($key) {
                $query->where('user_name', 'LIKE', '%' . $key . '%')
                    ->orWhere('first_name', 'LIKE', '%' . $key . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $key . '%');
            });
        }

        return $users->get();
    }
}