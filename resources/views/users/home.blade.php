@extends('layouts.app')

@section('content')
<style>
    .theme-card {
        background-color: #FFFFFF;
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: transform 0.2s ease-in-out;
    }
    .theme-card:hover {
        transform: translateY(-5px);
    }
    .profile-box {
        background-color: #A2AF9B;
        color: #fff;
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .profile-box a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
    }
    .profile-box p {
        color: #f0f0f0;
    }
    .suggestion-title {
        font-weight: bold;
        color: #6b705c;
    }
    .btn-follow {
        color: #A2AF9B;
        font-weight: bold;
        transition: color 0.2s ease;
    }
    .btn-follow:hover {
        color: #6b705c;
    }
</style>

<div class="row gx-5">
    <!-- Left Column (Posts) -->
    <div class="col-8">
        @forelse ($home_posts as $post)
            <div class="card theme-card mb-4">
                {{-- Title --}}
                @include('users.posts.contents.title')

                {{-- Body --}}
                @include('users.posts.contents.body')
            </div>
        @empty
            <div class="text-center py-5">
                <h2 class="fw-bold" style="color:#6b705c;">Share Photos</h2>
                <p class="text-muted">When you share photos, they'll appear on your profile</p>
                <a href="{{ route('post.create') }}" 
                   class="btn btn-outline-success rounded-pill px-4" 
                   style="border-color:#A2AF9B; color:#A2AF9B;">
                   Share your first photo
                </a>
            </div>
        @endforelse
    </div>

    <!-- Right Column (Profile + Suggestions) -->
    <div class="col-4">
        <!-- Profile Overview -->
        <div class="row align-items-center mb-5 profile-box">
            <div class="col-auto">
                <a href="{{ route('profile.show', Auth::user()->id) }}">
                    @if (Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="rounded-circle avatar-md border border-2 border-white">
                    @else
                        <i class="fa-solid fa-circle-user text-light icon-md"></i>
                    @endif
                </a>
            </div>
            <div class="col ps-0">
                <a href="{{ route('profile.show', Auth::user()->id) }}">
                    {{ Auth::user()->name }}
                </a>
                <p class="mb-0">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <!-- Suggestions -->
        @if ($suggested_users)
            <div class="row mb-3">
                <div class="col-auto">
                    <p class="suggestion-title">Suggestions For You</p>
                </div>
                <div class="col text-end">
                    <a href="#" class="fw-bold text-dark text-decoration-none">See all</a>
                </div>
            </div>

            @foreach ($suggested_users as $user)
                <div class="row align-items-center mb-3">
                    <div class="col-auto">
                        <a href="{{ route('profile.show', $user->id) }}">
                            @if ($user->avatar)
                                <img src="{{ $user->avatar }}" 
                                     alt="{{ $user->name }}" 
                                     class="rounded-circle avatar-sm border border-1 border-secondary">
                            @else
                                <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                            @endif
                        </a>
                    </div>
                    <div class="col ps-0 text-truncate">
                        <a href="{{ route('profile.show', $user->id) }}" 
                           class="text-decoration-none text-dark fw-bold">
                           {{ $user->name }}
                        </a>
                    </div>
                    <div class="col-auto">
                        <form action="{{ route('follow.store', $user->id) }}" method="post">
                            @csrf
                            <button type="submit" class="border-0 bg-transparent p-0 btn-follow">Follow</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
