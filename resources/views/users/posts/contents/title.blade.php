<div class="card-header border-0 py-3" style="background-color:#FAF9EE; border-radius: 15px;">
    <div class="d-flex justify-content-between align-items-center">

        {{-- Left side: avatar + name --}}
        <div class="d-flex align-items-center">
            <a href="{{ route('profile.show', $post->user->id) }}">
                @if ($post->user->avatar)
                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}"
                        class="rounded-circle avatar-sm me-2 border" style="border:2px solid #A2AF9B;">
                @else
                    <i class="fa-solid fa-circle-user text-secondary icon-sm me-2"></i>
                @endif
            </a>
            <div>
                <a href="{{ route('profile.show', $post->user->id) }}" class="fw-bold text-decoration-none"
                    style="color:#4A4A4A;">{{ $post->user->name }}</a>
                <p class="mb-0 small text-muted">Posted {{ $post->created_at->diffForHumans() }}</p>
            </div>
        </div>

        {{-- Right side: dropdown menu --}}
        <div class="dropdown">
            <button class="btn btn-sm shadow-none text-muted" data-bs-toggle="dropdown">
                <i class="fa-solid fa-ellipsis"></i>
            </button>

            @if (Auth::user()->id === $post->user->id)
                <div class="dropdown-menu dropdown-menu-end shadow-sm rounded-3">
                    <a href="{{ route('post.edit', $post->id) }}" class="dropdown-item">
                        <i class="fa-regular fa-pen-to-square"></i> Edit
                    </a>
                    <button class="dropdown-item text-danger" data-bs-toggle="modal"
                        data-bs-target="#delete-post-{{ $post->id }}">
                        <i class="fa-regular fa-trash-can"></i> Delete
                    </button>
                </div>

                {{-- Include delete modal --}}
                @include('users.posts.contents.modals.delete')
            @else
                <div class="dropdown-menu dropdown-menu-end shadow-sm rounded-3">
                    <form action="{{ route('follow.destroy', $post->user->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fa-solid fa-user-xmark"></i> Unfollow
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* General dropdown menu styling */
    .dropdown-menu {
        border: none;
        border-radius: 12px;
        padding: 0.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        min-width: 90px;
    }

    /* Default dropdown items */
    .dropdown-item {
        font-weight: 500;
        padding: 0.5em;
        color: #2C2C2C !important;
        display: flex;
        align-items: center;
        border-radius: 8px;
        margin: 2px;
        transition: all 0.25s ease-in-out;
    }

    /* Hover effect with your brand color */
    .dropdown-item:hover {
        background-color: #A2AF9B !important;
        color: #fff !important;
    }

    /* Ensure icons follow text color */
    .dropdown-item i {
        transition: color 0.25s ease-in-out;
    }
    .dropdown-item:hover i {
        color: #fff !important;
    }

    /* Delete & Unfollow danger buttons */
    .dropdown-item.text-danger {
        color: #d9534f !important;
    }

    .dropdown-item.text-danger:hover {
        background-color: #d9534f !important;
        color: #fff !important;
    }

    .dropdown-item.text-danger:hover i {
        color: #fff !important;
    }
</style>
