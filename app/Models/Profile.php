<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'avatar',
        'cover',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function followers(){
        return $this->belongsToMany(User::class);
    }

    public function profileImages(){
        return $this->hasMany(ProfileImage::class);
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }
}
