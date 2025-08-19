@extends('layouts.app')

@section('title', 'Explore')

@section('content')
<div class="container py-3" style="max-width: 980px;">
  <h1 class="h5 text-muted mb-3"><i class="fa-solid fa-globe me-2"></i>Explore</h1>

  <div class="row g-2">
    @forelse($posts as $post)
      <div class="col-4 col-md-3 col-lg-2">
        {{-- カード全体をリンク化：そのユーザーの投稿ページへ --}}
        <a href="{{ route('profile.show', $post->user_id) }}"
           class="d-block ratio ratio-1x1 rounded overflow-hidden bg-light">
          <img src="{{ $post->image }}" alt="post image"
               class="w-100 h-100" style="object-fit: cover;">
        </a>
      </div>
    @empty
      <div class="col-12 text-center text-muted py-5">No posts yet</div>
    @endforelse
  </div>

  <div class="mt-3">
    {{ $posts->links() }}
  </div>
</div>
@endsection
