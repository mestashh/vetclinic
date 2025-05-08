@extends('layouts.app')

@section('title', 'Новости')

@section('content')
    <div class="layout-center">
        <div class="layout-container">
            <h1 style="text-align: center; font-size: 1.5rem; font-weight: bold;">Новости</h1>
            <div id="newsRoot">Привет мир</div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.currentUserRole = "{{ Auth::check() ? Auth::user()->role : '' }}";
    </script>
    @vite(['src/app.js'])
@endsection
