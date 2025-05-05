<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vetclinic')</title>
    <link rel="stylesheet" href="{{ asset('frontend/main.css') }}">
</head>
<body>
<header>
    <nav>
        <a href="{{ route('home') }}">Главная</a>
        <a href="{{ route('clients.index') }}">Клиенты</a>
        <a href="{{ route('appointments.index') }}">Приёмы</a>
    </nav>
</header>

<main id="app"> <!-- Обратите внимание на этот элемент -->
    @yield('content')
</main>


</body>
</html>
