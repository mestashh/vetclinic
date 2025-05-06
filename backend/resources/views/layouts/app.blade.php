<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>@yield('title', 'VetClinic')</title>
</head>
<body>
@include('layouts.navigation') <!-- Навигация сверху -->

<div class="p-4">
    @yield('content') <!-- Основной контент -->
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
