@extends('layouts.app')

@section('title', 'Проведение приёма')

@section('content')
    <style>
        .appointment-selector {
            max-width: 800px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        select, button {
            width: 100%;
            padding: 0.5rem;
            margin-top: 1rem;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #2563eb;
            color: white;
            cursor: pointer;
        }

        .appointment-details {
            margin-top: 2rem;
            background: #f9fafb;
            padding: 1rem;
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .appointment-details p {
            margin-bottom: 0.5rem;
        }

        .toggle-btn {
            background: none;
            border: none;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            padding: 0;
            margin: 0 0.4rem 0 0;
            color: #333;
            line-height: 1;
            display: inline-block;
            transform: translateY(1px);
            width: auto;
        }

        .toggle-btn:hover {
            color: #2563eb;
        }
    </style>

    <div class="appointment-selector">
        <h1 class="text-xl font-bold mb-4">Выберите приём</h1>

        <select id="appointmentSelect">
            <option value="">— выберите приём —</option>
        </select>

        <div id="appointmentInfo" class="appointment-details" style="display:none;"></div>
    </div>
@endsection

@section('scripts')
    <script>
        window.currentUserRole = '{{ Auth::user()->role }}';
        window.currentUserId = {{ Auth::id() }};
    </script>
    @vite(['src/app.js'])
@endsection
