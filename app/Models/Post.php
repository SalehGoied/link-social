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
        'can_sharing',
        'can_comment',
        'post_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    public function reacts()
    {
        return $this->morphMany(React::class, 'reactable');
    }

    public function saved_posts()
    {
        return $this->hasMany(SavedPost::class)->latest();
    }

    public function parent()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function children()
    {
        return $this->hasMany(Post::class, 'post_id');
    }

    public function destory()
    {

        $this->photos()->delete();

        $this->comments()->destory();

        $this->reacts()->delete();

        // foreach ($this->comments as $comment) {
        //     $comment->delete();
        // }

        // foreach ($this->reacts as $react) {
        //     $react->delete();
        // }

        $this->delete();
    }
}
