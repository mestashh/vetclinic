@extends('layouts.app')

@section('content')
    <div class="p-4">
        <h1 class="text-3xl font-bold mb-4">Питомцы</h1>
        <ul class="space-y-2">
            @foreach($pets as $pet)
                <li class="border p-3 rounded">
                    <strong>{{ $pet->name }}</strong> ({{ $pet->species }}, {{ $pet->breed }})<br>
                    Дата рождения: {{ $pet->birth_date }}<br>
                    Владелец: {{ $pet->client->first_name }} {{ $pet->client->last_name }}
                </li>
            @endforeach

            @if($pets->isEmpty())
                <li>Питомцев пока нет.</li>
            @endif
        </ul>
    </div>
@endsection
