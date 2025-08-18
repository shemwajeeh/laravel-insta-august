<?php

namespace App\Models;

// use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    public function user() {
        return $this->belongsTo(User::class)->withTrashed();
    }

    #To get the categories under post
    public function categoryPost() {
        return $this->hasMany(CategoryPost::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    #To get the likes of a post
    public function likes() {
        return $this->hasMany(Like::class);
    }

    #Returns TRUE if the logged in user already liked the post
    public function isLiked() {
        return $this->likes()->where('user_id', Auth::user()->id)->exists();
    }
}
