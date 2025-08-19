<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    {{-- <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <span class="brand-text">{{ config('app.name') }}</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    {{-- [SOON] Add Search Bar Here --}}
                    @auth
                        {{-- This will not show up in the admin side --}}
                        {{-- @if (!request()->is('admin/*'))
                            <ul class="navbar-nav ms-auto">
                                <form action="{{ route('search') }}" class="d-flex" style="width: 300px;">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fa-solid fa-magnifying-glass text-muted"></i>
                                        </span>
                                        <input type="search" name="search" class="form-control border-start-0 shadow-none"
                                            placeholder="Search..." aria-label="Search">
                                    </div>
                                </form>
                            </ul>
                        @endif --}}
                    @endauth


                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            {{-- @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif --}}
                        @else
                            {{-- find others posts --}}
                            <li class="nav-item" title="Explore">
                                <a href="{{ route('explore') }}" class="nav-link">
                                    <i class="fa-solid fa-globe icon-sm" style="color:#A2AF9B;"></i>
                                </a>
                            </li>
                            
                            {{-- Search --}}
                            <li class="nav-item dropdown" title="Search">
                                <button class="btn shadow-none nav-link" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-magnifying-glass icon-sm" style="color: #A2AF9B;"></i>
                                </button>
                                <div class="dropdown-menu p-3 shadow border-0 rounded-3"
                                    style="min-width: 280px; background-color: #FAF9EE;">
                                    <form action="{{ route('search') }}" method="GET" class="d-flex">
                                        <input type="search" name="search"
                                            class="form-control form-control-sm me-2 rounded-pill shadow-sm"
                                            placeholder="Search..."
                                            style="flex: 1; border: 1px solid #A2AF9B; background-color: #fff;">
                                        <button type="submit" class="btn btn-sm rounded-pill px-3"
                                            style="background-color: #A2AF9B; color: #fff;">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>

                            {{-- Home --}}
                            <li class="nav-item" title="Home">
                                <a href="{{ route('index') }}" class="nav-link"><i class="fa-solid fa-house  icon-sm"
                                        style="color: #A2AF9B;"></i></a>
                            </li>

                            {{-- Create Post --}}
                            <li class="nav-item" title="Create Post">
                                <a href="{{ route('post.create') }}" class="nav-link"><i
                                        class="fa-solid fa-circle-plus icon-sm" style="color: #A2AF9B;"></i></a>
                            </li>

                            {{-- Account --}}
                            <li class="nav-item dropdown">
                                <button id="account-dropdown" class="btn shadow-none nav-link" data-bs-toggle="dropdown">
                                    @if (Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}"
                                            class="rounded-circle avatar-sm border border-2" style="border-color:#A2AF9B;">
                                    @else
                                        <i class="fa-solid fa-circle-user icon-sm"
                                            style="color:#A2AF9B; border:2px solid #A2AF9B; border-radius:50%;"></i>
                                    @endif
                                </button>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="account-dropdown">
                                    {{-- [SOON] Admin Controls --}}
                                    {{-- @can('admin') --}}
                                    @if (Gate::allows('admin'))
                                        <a href="{{ route('admin.users') }}" class="dropdown-item">
                                            <i class="fa-solid fa-user-gear"></i> Admin
                                        </a>

                                        <hr class="dropdown-divider">
                                        {{-- @endcan --}}
                                    @endif

                                    {{-- Profile --}}
                                    <a href="{{ route('profile.show', Auth::user()->id) }}" class="dropdown-item">
                                        <i class="fa-solid fa-circle-user"></i> Profile
                                    </a>

                                    {{-- Logout --}}
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-right-from-bracket"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    {{-- [SOON] Admin Controls --}}
                    @if (request()->is('admin/*'))
                        <div class="col-3">
                            <div class="list-group">
                                <a href="{{ route('admin.users') }}"
                                    class="list-group-item {{ request()->is('admin/users') ? 'active' : '' }}">
                                    <i class="fa-solid fa-users"></i> Users
                                </a>
                                <a href="{{ route('admin.posts') }}"
                                    class="list-group-item {{ request()->is('admin/posts') ? 'active' : '' }}">
                                    <i class="fa-solid fa-newspaper"></i> Posts
                                </a>
                                <a href="{{ route('admin.categories') }}"
                                    class="list-group-item {{ request()->is('admin/categories') ? 'active' : '' }}">
                                    <i class="fa-solid fa-tags"></i> Categories
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="col-9">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
