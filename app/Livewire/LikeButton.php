<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;

class LikeButton extends Component
{
    public $post;
    public $isLiked = false;
    public $likesCount = 0;
    public $showLikesModal = false;
    public $likedUsers = [];

    public function mount($post)
    {
        $this->post = $post;
        $this->refreshLikeState();
    }

    public function showLikes($postId)
    {
        $this->likedUsers = Like::where('post_id', $postId)
            ->join('users', 'likes.user_id', '=', 'users.id')
            ->pluck('users.name')
            ->toArray();

        $this->dispatch('openLikesModal', postId: $postId);
    }


    public function toggleLike()
    {
        if ($this->isLiked) {
            Like::where('user_id', Auth::id())
                ->where('post_id', $this->post->id)
                ->delete();
        } else {
            Like::create([
                'user_id' => Auth::id(),
                'post_id' => $this->post->id,
            ]);
        }

        $this->refreshLikeState();
    }

    private function refreshLikeState()
    {
        $likes = $this->post->likes()->with('user')->get();

        $this->isLiked = $likes->where('user_id', Auth::id())->isNotEmpty();
        $this->likesCount = $likes->count();
        $this->likedUsers = $likes->pluck('user.name')->toArray();
    }

    public function render()
    {
        return view('livewire.like-button');
    }
}
