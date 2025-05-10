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

        .pet-info, .appointments {
            margin-top: 1.5rem;
        }

        .appointment {
            margin-bottom: 1rem;
            background: #f9fafb;
            padding: 1rem;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        .appointment h4 {
            margin-bottom: 0.5rem;
        }
    </style>

    <div class="pet-history-wrapper">
        <h1>История болезней</h1>
        <div id="petInfo" class="pet-info">
            <p>Загрузка...</p>
        </div>

        <div id="appointments" class="appointments"></div>
    </div>
@endsection

@section('scripts')
    <script>window.currentPetId = {{ $id }};</script>
    @vite(['src/app.js'])
@endsection
