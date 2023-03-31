<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'body',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'user_name', 'email')
        ->with('profile:user_id,cover,avatar,description');
    }

    public function reacts()
    {
        return $this->morphMany(React::class, 'reactable');
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable')->select('photoable_id','path');
    }

    public function destory()
    {
        $this->reacts()->delete();

        $this->photos()->delete();

        $this->delete();
    }
}
