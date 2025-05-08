@extends('layouts.app')
@section('content')
@vite(['src/app.js'])
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-4 py-12">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
        <div class="md:flex">
            <div class="p-8">
                <h2 class="text-xl font-bold mb-4">Вход в аккаунт</h2>

                @if ($errors->any())
                    <div class="mb-4 text-red-500">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-4">
                        <label>Email</label>
                        <input type="email" name="email" required class="border p-2 w-full">
                    </div>

                    <div class="mb-4">
                        <label>Пароль</label>
                        <input type="password" name="password" required class="border p-2 w-full">
                    </div>

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                        Войти
                    </button>
                </form>

                <div class="mt-4">
                    <span>Нет аккаунта?</span>
                    <a href="{{ route('register') }}" class="text-blue-500">Зарегистрироваться</a>
                </div>

            </div>
        </div>
    </div>
</div>
</body>
</html>
@endsection


