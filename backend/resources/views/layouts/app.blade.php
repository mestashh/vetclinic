<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['src/app.css', 'src/app.js'])
    <title>@yield('title', 'VetClinic')</title>
</head>
<body>
@include('layouts.navigation')

<div class="p-4">
    @yield('content')
</div>

</body>
</html>
