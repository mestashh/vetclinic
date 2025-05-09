@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <style>
        .register-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f3f4f6;
        }

        .register-box {
            background: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 480px;
        }

        .register-box h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: #111827;
        }

        .register-box label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .register-box input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            margin-bottom: 1rem;
            box-sizing: border-box;
        }

        .register-box button {
            width: 100%;
            padding: 0.6rem;
            background-color: #10b981;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .register-box button:hover {
            background-color: #059669;
        }

        .register-box .bottom-text {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .register-box .bottom-text a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .register-box .bottom-text a:hover {
            text-decoration: underline;
        }

        .register-error {
            text-align: left;
            color: red;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        .input-group {
            display: flex;
            gap: 0.5rem;
        }

        .input-group input {
            flex: 1;
        }
    </style>

    <div class="register-wrapper">
        <div class="register-box">
            <h2>Регистрация</h2>

            @if ($errors->any())
                <div class="register-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <label for="last_name">Фамилия *</label>
                <input type="text" id="last_name" name="last_name" required>

                <label for="first_name">Имя *</label>
                <input type="text" id="first_name" name="first_name" required>

                <label for="middle_name">Отчество</label>
                <input type="text" id="middle_name" name="middle_name">

                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required>

                <label for="phone">Телефон *</label>
                <input type="tel" id="phone" name="phone" required>

                <label for="password">Пароль *</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Зарегистрироваться</button>
            </form>

            <div class="bottom-text">
                Уже есть аккаунт?
                <a href="{{ route('login') }}">Войти</a>
            </div>
        </div>
    </div>
@endsection
