<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class React extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
    ];

    public function post(){
        return $this->belongsTo(Post::class);
    }

    public function user(){
        return $this->belongsTo(User::class)->select('id', 'user_name', 'email')
        ->with('profile:user_id,cover,avatar,description');
    }

    public function reactable(){
        return $this->morphTo();
    }
}
