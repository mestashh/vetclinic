@extends('layouts.app')

@section('title', 'Управление ролями')

@section('content')
    <div class="layout-center">
        <div class="layout-container">
            <h1 class="text-xl font-bold mb-4">Назначение ролей</h1>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table-clean w-full">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Текущая роль</th>
                    <th>Изменить роль</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $user->id }}</td>
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ $user->role }}</td>
                        <td class="px-4 py-2">
                            <form action="{{ route('roles.update', $user) }}" method="POST" class="flex space-x-2">
                                @csrf
                                @method('PUT')
                                <select name="role" class="border rounded px-2 py-1">
                                    <option value="user" @selected($user->role === 'user')>Клиент</option>
                                    <option value="vet" @selected($user->role === 'vet')>Врач</option>
                                    <option value="admin" @selected($user->role === 'admin')>Админ</option>
                                    <option value="superadmin" @selected($user->role === 'superadmin')>Главный админ</option>
                                </select>
                                <button class="bg-blue-500 text-white px-2 rounded">Сохранить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
