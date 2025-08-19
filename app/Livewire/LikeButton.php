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
    public $likesDisplay = '';
    public $showLikesModal = false;
    public $likedUsers = [];

    public function mount($post)
    {
        $this->post = $post;
        $this->refreshLikeState();
    }

    public function showLikes()
    {
        $this->likedUsers = $this->post->likes()
            ->join('users', 'likes.user_id', '=', 'users.id')
            ->pluck('user.name')
            ->toArray();

        $this->showLikesModal = true;
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
        // Reload likes with users
        $likes = $this->post->likes()->with('user')->get();
        $likedUsers = $likes->pluck('user.name');

        $this->isLiked = $likes->where('user_id', Auth::id())->isNotEmpty();
        $this->likesCount = $likes->count();

        // Build display string
        if ($this->likesCount === 0) {
            $this->likesDisplay = '';
        } elseif ($this->likesCount === 1) {
            $this->likesDisplay = "Liked by {$likedUsers[0]}";
        } else {
            $othersCount = $this->likesCount - 1;
            $this->likesDisplay = "Liked by {$likedUsers[0]} and <a href='#' wire:click='showLikes' class='fw-semibold text-decoration-none text-dark'>{$othersCount} others</a>";
        }
    }

    public function render()
    {
        return view('livewire.like-button');
    }
}
