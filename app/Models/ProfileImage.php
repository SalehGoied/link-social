<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'path', 'profile_id', 'type'
    ];

    public function profile(){
        return $this->belongsTo(Profile::class);
    }
}
