{{-- clickable image --}}
<div class="container p-0 post-image-wrapper">
    <a href="{{ route('post.show', $post->id) }}">
        <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="w-100 rounded-3 shadow-sm post-image">
    </a>
</div>

<div class="card-body">
    {{-- heart button + no. of likes + categories --}}
    <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="d-flex align-items-center">
            @livewire('like-button', ['post' => $post], key($post->id))
            {{-- @if ($post->isLiked())
                <form action="{{ route('like.destroy', $post->id) }}" method="post" class="me-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm shadow-none p-0 like-btn">
                        <i class="fa-solid fa-heart text-danger fs-5"></i>
                    </button>
                </form>
            @else
                <form action="{{ route('like.store', $post->id) }}" method="post" class="me-2">
                    @csrf
                    <button type="submit" class="btn btn-sm shadow-none p-0 like-btn">
                        <i class="fa-regular fa-heart fs-5"></i>
                    </button>
                </form>
            @endif --}}
            {{-- <span class="ms-2 fw-semibold small">{{ $post->likes->count() }} likes</span> --}}
        </div>

        <div>
            @forelse ($post->categoryPost as $category_post)
                <span class="badge rounded-pill bg-dark bg-opacity-75 text-white px-3 py-1">
                    {{ $category_post->category->name }}
                </span>
            @empty
                <span class="badge rounded-pill bg-secondary px-3 py-1">Uncategorized</span>
            @endforelse
        </div>
    </div>

    {{-- owner + description --}}
    <p class="mb-1">
        <a href="{{ route('profile.show', $post->user->id) }}"
            class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a>
        <span class="fw-light ms-1">{{ $post->description }}</span>
    </p>

    <p class="text-uppercase text-muted small mb-2">{{ date('M d, Y', strtotime($post->created_at)) }}</p>

    {{-- comments --}}
    @include('users.posts.contents.comments.comments')
</div>

{{-- Extra CSS --}}
<style>
    .post-image {
        height: 350px;
        object-fit: cover;
        border-radius: 8px;
    }

    .post-image-wrapper {
        overflow: hidden;
        border-radius: 12px;
    }

    /* Like button animation */
    .like-btn i {
        transition: transform 0.2s ease;
    }

    .like-btn:hover i {
        transform: scale(1.3);
    }
</style>
