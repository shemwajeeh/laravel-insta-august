@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <style>
        body {
            background-color: #FAF9EE;
        }

        .profile-header {
            background: #fff;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .avatar-lg {
            width: 120px;
            height: 120px;
            border: 4px solid #A2AF9B;
            object-fit: cover;
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

        .post-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            transition: all 0.3s ease-in-out;
        }

        .post-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            display: block;
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.2s ease;
        }

        .post-card:hover {
            border: 3px solid #A2AF9B;
            /* same color as theme */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .post-card:hover img {
            transform: scale(1.05);
        }

        a.text-dark {
            color: #2C2C2C !important;
        }

        a.text-dark:hover {
            color: #A2AF9B !important;
        }
    </style>

    @include('users.profile.header')

    {{-- show all posts here --}}
    <div style="margin-top: 60px">
        @if ($user->posts->isNotEmpty())
            <div class="row">
                @foreach ($user->posts as $post)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="post-card">
                            <a href="{{ route('post.show', $post->id) }}">
                                <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="grid-img">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <h3 class="text-muted text-center">No Posts Yet</h3>
        @endif
    </div>
@endsection
