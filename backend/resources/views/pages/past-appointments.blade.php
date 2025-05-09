@extends('layouts.app')

@section('title', 'История приёмов')

@section('content')
    <style>
        .appointments-wrapper {
            max-width: 1000px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            font-family: sans-serif;
        }

        h1 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        tr:hover {
            background-color: #f9fafb;
        }

        .details-row {
            background-color: #f9fafb;
        }

        .show-details-btn {
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 0.3rem 0.7rem;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .show-details-btn:hover {
            background: #1e40af;
        }
    </style>

    <div class="appointments-wrapper">
        <h1>История приёмов</h1>
        <table id="pastAppointmentsTable">
            <thead>
            <tr>
                <th colspan="4">Завершённые приёмы</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        window.currentUserId = {{ Auth::id() }};
    </script>
    @vite(['src/app.js'])
@endsection
