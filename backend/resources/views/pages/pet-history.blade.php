@extends('layouts.app')

@section('title', 'История болезней')

@section('content')
    <style>
        .pet-history-wrapper {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            font-family: sans-serif;
        }
    </style>

    <div class="pet-history-wrapper">
        <h1>История болезней</h1>
        <p id="helloText">Привет, мир!</p>
        <p><strong>ID питомца:</strong> <span id="petId">{{ $id }}</span></p>
    </div>
@endsection

@section('scripts')
    <script>window.currentPetId = {{ $id }};</script>
    @vite(['src/app.js'])
@endsection
