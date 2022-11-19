<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'body',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function files(){
        return $this->hasMany(PostFile::class);
    }

    public function reacts(){
        return $this->hasMany(React::class)->latest();
    }

    public function destory(){

        foreach($this->files as $file){
            // $path = $file->path;
            $file->delete();

            // if(File::exists($path)){
            //     File::delete($path);
            // }
        }

        foreach($this->comments as $comment){
            $comment->delete();
        }

        foreach($this->reacts as $react){
            $react->delete();
        }

        $this->delete();
    }
}
