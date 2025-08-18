@extends('layouts.app')

@section('title', "Login")

@section('content')
<style>

    .login-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        background: #fff;
        width: 450px;
        margin: auto;
    }
    .login-header {
        font-size: 2rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 25px;
        color: #444;
    }
    .btn-custom {
        background: #A2AF9B;
        border: none;
        color: white;
        font-weight: bold;
        border-radius: 30px;
        padding: 12px;
        width: 100%;
        transition: 0.3s;
    }
    .btn-custom:hover {
        background: #8d987f;
    }
    .form-label {
        font-weight: 500;
        color: #555;
    }
    .form-control {
        border-radius: 12px;
        padding: 10px 12px;
        border: 1px solid #ccc;
    }
    .form-control:focus {
        border-color: #A2AF9B;
        box-shadow: 0 0 0 0.2rem rgba(162, 175, 155, 0.3);
    }
    .extra-links {
        text-align: center;
        margin-top: 15px;
    }
    .extra-links a {
        color: #A2AF9B;
        text-decoration: none;
        font-size: 0.9rem;
    }
    .extra-links a:hover {
        text-decoration: underline;
    }
</style>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card login-card p-5">
        <div class="login-header">Welcome Back!</div>

        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input id="email" type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-custom">
                    {{ __('Login') }}
                </button>

                <div class="extra-links">
                    <a href="{{ route('password.request') }}">Forgot password?</a><br>
                    <a href="{{ route('register') }}">Don't have an account? Sign up</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
