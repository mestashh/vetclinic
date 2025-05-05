@extends('layouts.app')

@section('title', 'Панель администратора')

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Панель администратора</h1>
        <ul>
            <li><a href="{{ route('clients.index') }}">Управление клиентами</a></li>
            <li><a href="{{ route('appointments.index') }}">Управление приемами</a></li>
            <li><a href="{{ route('veterinarians.index') }}">Управление ветеринарами</a></li>
        </ul>
    </div>
@endsection
