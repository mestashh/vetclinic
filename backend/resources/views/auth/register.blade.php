@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Создание аккаунта</h2>

        @if($errors->any())
            <div class="text-red-500 mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label>Имя</label>
                <input type="text" name="name" required class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" required class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label>Пароль</label>
                <input type="password" name="password" required class="w-full border p-2 rounded">
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                Зарегистрироваться
            </button>
        </form>
    </div>
@endsection
