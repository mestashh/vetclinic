@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <style>
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f3f4f6;
        }

        .login-box {
            background: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: #111827;
        }

        .login-box label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .login-box input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            margin-bottom: 1rem;
            box-sizing: border-box;
        }

        .login-box button {
            width: 100%;
            padding: 0.6rem;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .login-box button:hover {
            background-color: #1d4ed8;
        }

        .login-box .bottom-text {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .login-box .bottom-text a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .login-box .bottom-text a:hover {
            text-decoration: underline;
        }

        .login-error {
            text-align: center;
            color: red;
            margin-bottom: 1rem;
        }
    </style>

    <div class="login-wrapper">
        <div class="login-box">
            <h2>Вход в аккаунт</h2>

            @if(session('error'))
                <div class="login-error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label for="email">Email</label>
                <input id="email" type="email" name="email" required autofocus>

                <label for="password">Пароль</label>
                <input id="password" type="password" name="password" required>

                <button type="submit">Войти</button>
            </form>

            <div class="bottom-text">
                Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a>
            </div>
        </div>
    </div>
@endsection
