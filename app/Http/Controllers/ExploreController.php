<?php

namespace App\Http\Controllers;

use App\Models\Post;

class ExploreController extends Controller
{
    public function index()
    {
        $posts = Post::query()
            ->select('id', 'user_id', 'image', 'created_at') // 画像表示に必要な最小限
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->whereNull('deleted_at')        // 隠し(SoftDelete)を除外しているなら
            // ->where('is_private', false)   // 非公開フラグがあるなら使う
            ->orderByDesc('created_at')
            ->paginate(36);                  // サムネなら少し多めに

        return view('explore.index', compact('posts'));
    }
}
