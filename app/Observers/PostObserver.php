<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\PostCaptionTranslation;

class PostObserver
{
    public function updating(Post $post): void
    {
        if ($post->isDirty('caption')) {
            PostCaptionTranslation::where('post_id', $post->id)->delete();
        }
    }
}
