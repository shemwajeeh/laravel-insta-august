@extends('layouts.app')

@section('content')
    <style>
        .auth-container {
            width: 450px;
            margin: 80px auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .form-wrapper {
            display: flex;
            width: 200%;
            transition: transform 0.6s ease-in-out;
        }

        .auth-container.slide-register .form-wrapper {
            transform: translateX(-50%);
        }

        .login{
            margin: 40px auto
        }

        .form-box {
            width: 50%;
            padding: 40px;
        }

        .form-box h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
            color: #333;
        }

        .form-box input {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
        }

        .form-box button {
            width: 100%;
            padding: 12px;
            background: #A2AF9B;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .form-box button:hover {
            background: #8F9C87;
        }

        .toggle-btn {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            cursor: pointer;
            color: #555;
        }

        .toggle-btn span {
            color: #A2AF9B;
            font-weight: 600;
            cursor: pointer;
        }
    </style>

    <div class="auth-container" id="authBox">
        <div class="form-wrapper">

            {{-- Login Form --}}
            <div class="form-box">
                <div class="login">
                    <h2>Login</h2>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <input type="email" name="email" placeholder="Email" required autofocus>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit">Login</button>
                    </form>
                    <div class="toggle-btn">
                        Don't have an account? <span onclick="toggleSlide()">Register</span>
                    </div>
                </div>
            </div>

            {{-- Register Form --}}
            <div class="form-box">
                <h2>Register</h2>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="text" name="name" placeholder="Full Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                    <button type="submit">Register</button>
                </form>
                <div class="toggle-btn">
                    Already have an account? <span onclick="toggleSlide()">Login</span>
                </div>
            </div>

        </div>
    </div>

    <script>
        function toggleSlide() {
            document.getElementById('authBox').classList.toggle('slide-register');
        }
    </script>
@endsection
