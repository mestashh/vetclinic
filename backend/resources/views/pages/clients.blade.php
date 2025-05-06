@extends('layouts.app')

@section('content')
    <div class="p-4">
        <h1 class="text-3xl font-bold mb-4">Клиенты</h1>
        <ul class="space-y-2">
            @foreach($clients as $client)
                <li class="border p-3 rounded">
                    <strong>{{ $client->first_name }} {{ $client->middle_name }} {{ $client->last_name }}</strong><br>
                    Email: {{ $client->email }}<br>
                    Телефон: {{ $client->phone }}<br>
                    Адрес: {{ $client->address }}
                </li>
            @endforeach

            @if($clients->isEmpty())
                <li>Клиентов пока нет.</li>
            @endif
        </ul>
    </div>
@endsection

