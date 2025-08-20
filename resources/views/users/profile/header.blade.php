<div class="profile-header">
    <div class="row align-items-center">
        {{-- Avatar --}}
        <div class="col-md-4 text-center mb-3 mb-md-0">
            @if ($user->avatar)
                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="avatar-lg rounded-circle">
            @else
                <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-lg"></i>
            @endif
        </div>

        {{-- Info --}}
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-3 flex-wrap gap-2">
                <h2 class="profile-name mb-0 me-3">{{ $user->name }}</h2>

                @if (Auth::user()->id === $user->id)
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm fw-bold">Edit Profile</a>
                @else
                    @if ($user->isFollowed())
                        <form action="{{ route('follow.destroy', $user->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary btn-sm fw-bold">Following</button>
                        </form>
                    @else
                        <form action="{{ route('follow.store', $user->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm fw-bold">Follow</button>
                        </form>
                    @endif
                @endif
            </div>

            {{-- Stats --}}
            <div class="d-flex gap-4 mb-3">
                <a href="{{ route('profile.show', $user->id) }}" class="profile-stat">
                    <strong>{{ $user->posts->count() }}</strong> {{ Str::plural('post', $user->posts->count()) }}
                </a>
                <a href="{{ route('profile.followers', $user->id) }}" class="profile-stat">
                    <strong>{{ $user->followers->count() }}</strong> {{ Str::plural('follower', $user->followers->count()) }}
                </a>
                <a href="{{ route('profile.following', $user->id) }}" class="profile-stat">
                    <strong>{{ $user->following->count() }}</strong> following
                </a>
            </div>

            {{-- Introduction --}}
            <p class="profile-intro">{{ $user->introduction }}</p>
        </div>
    </div>
</div>

<style>
    .profile-header {
        background: #fff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }

    .avatar-lg {
        width: 140px;
        height: 140px;
        border: 4px solid #A2AF9B;
        object-fit: cover;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }

    .avatar-lg:hover {
        transform: scale(1.03);
    }

    .profile-name {
        font-size: 1.8rem;
        font-weight: 600;
        color: #2C2C2C;
    }

    .btn-primary {
        background-color: #A2AF9B !important;
        border: none !important;
    }

    .btn-primary:hover {
        background-color: #7D8A6F !important;
    }

    .btn-outline-secondary {
        border: 2px solid #A2AF9B !important;
        color: #A2AF9B !important;
        background: transparent;
    }

    .btn-outline-secondary:hover {
        background-color: #A2AF9B !important;
        color: #fff !important;
    }

    .profile-stat {
        text-decoration: none;
        color: #2C2C2C !important;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .profile-stat:hover {
        color: #A2AF9B !important;
    }

    .profile-intro {
        font-weight: 500;
        color: #555;
    }
</style>
