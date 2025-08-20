<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = (int) $request->query('category_id'); // 選択中のカテゴリ
        $categories = Category::orderBy('name')->get(['id','name']);

        $posts = Post::query()
            ->select('id','user_id','image','created_at')
            ->whereNotNull('image')->where('image','!=','')
            // 非表示(SoftDeletes)を除外しているなら↓を有効化
            // ->whereNull('deleted_at')
            ->when($categoryId, function($q) use ($categoryId){
                $q->whereHas('categories', fn($t) => $t->where('categories.id', $categoryId));
            })
            ->orderByDesc('created_at')
            ->paginate(36)
            ->withQueryString();

        return view('explore.index', compact('posts','categories','categoryId'));
    }
}
