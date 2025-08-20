{{-- resources/views/explore/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Explore')

@section('content')
<div class="container py-3" style="max-width: 980px;">
  <h1 class="h5 text-muted mb-3"><i class="fa-solid fa-globe me-2"></i>Explore</h1>

  {{-- カテゴリ絞り込み --}}
  <form method="GET" class="row g-2 align-items-end mb-3">
    <div class="col-auto">
      <label class="form-label mb-1">Category</label>
      <select name="category_id" class="form-select">
        <option value="">All</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" {{ ($categoryId ?? null) == $c->id ? 'selected' : '' }}>
            {{ $c->name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-auto d-grid">
      <button class="btn btn-outline-secondary">Apply</button>
    </div>
    @if(!empty($categoryId))
      <div class="col-auto d-grid">
        <a href="{{ route('explore') }}" class="btn btn-link">Clear</a>
      </div>
    @endif
  </form>

  {{-- 画像だけのグリッド --}}
  <div class="row g-2">
    @forelse($posts as $post)
      <div class="col-4 col-md-3 col-lg-2">
        <a href="{{ route('profile.show', $post->user_id) }}"
           class="d-block ratio ratio-1x1 rounded overflow-hidden bg-light">
          <img src="{{ $post->image }}" alt="post image" class="w-100 h-100" style="object-fit: cover;">
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
