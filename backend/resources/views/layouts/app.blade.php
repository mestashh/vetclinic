<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VetClinic')</title>

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- CHANGED: пути к ассетам --}}
    @vite(['src/app.css', 'src/app.js'])
</head>
<body style="background-color: #909091;">

{{-- навигация --}}
@include('layouts.navigation')

{{-- основной контент --}}
<main class="container mx-auto px-4 py-6">
    @yield('content')
</main>

</body>
</html>
