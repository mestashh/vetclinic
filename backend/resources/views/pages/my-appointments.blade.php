@extends('layouts.app')

@section('title', 'Мои записи')

@section('content')
    <div class="layout-center">
        <div class="layout-container">
            <h1 style="text-align: center; font-size: 1.5rem; font-weight: bold;">Мои записи</h1>
            <div id="myAppointmentsRoot">Привет мир</div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.currentUserRole = "{{ Auth::check() ? Auth::user()->role : '' }}";
    </script>
    @vite(['src/app.js'])
@endsection
