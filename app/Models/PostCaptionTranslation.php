<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCaptionTranslation extends Model
{
    protected $fillable = ['post_id', 'lang', 'text'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
