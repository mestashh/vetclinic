<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Ветеринарная клиника')</title>
    @vite(['src/app.css', 'src/app.js'])
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col">
@include('layouts.navigation')

<div id="app" class="flex-grow container mx-auto p-6">
    @yield('content')
</div>
</body>
</html>
