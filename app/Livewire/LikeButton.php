<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;

class LikeButton extends Component
{
    public $post;
    public $isLiked;
    public $likesCount;
    public $likedUsers = [];
    public $showModal = false;

    public function mount($post)
    {
        $this->post = $post;
        $this->isLiked = $post->isLiked();
        $this->likesCount = $post->likes->count();
        $this->likedUsers = $post->likes()->with('user')->get()->pluck('user.name')->toArray();
    }

    public function toggleLike()
    {
        if ($this->isLiked) {
            $this->post->likes()->where('user_id', auth()->id())->delete();
            $this->isLiked = false;
            $this->likesCount--;
        } else {
            $this->post->likes()->create(['user_id' => auth()->id()]);
            $this->isLiked = true;
            $this->likesCount++;
        }


        $this->likedUsers = $this->post->likes()->with('user')->get()->pluck('user.name')->toArray();
    }

    // private function refreshLikeState()
    // {
    //     $likes = $this->post->likes()->with('user')->get();

    //     $this->isLiked = $likes->where('user_id', Auth::id())->isNotEmpty();
    //     $this->likesCount = $likes->count();
    //     $this->likedUsers = $likes->pluck('user.name')->toArray();
    // }

    public function toggleModal()
    {
        $this->showModal = !$this->showModal;
    }


    public function render()
    {
        return view('livewire.like-button');
    }
}
