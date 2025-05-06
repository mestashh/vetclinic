<nav style="background-color:#2D3748; color:white; padding:1rem;">
    <ul style="display:flex; flex-direction:row; gap:1rem; list-style:none; margin:0; padding:0;">
        <li><a href="/" style="text-decoration:none; color:white;">Главная</a></li>
        <li><a href="/clients" style="text-decoration:none; color:white;">Клиенты</a></li>
        <li><a href="/appointments" style="text-decoration:none; color:white;">Приёмы</a></li>
        <li><a href="/pets" style="text-decoration:none; color:white;">Питомцы</a></li>
        <li><a href="/services" style="text-decoration:none; color:white;">Услуги</a></li>
        <li><a href="/veterinarians" style="text-decoration:none; color:white;">Ветеринары</a></li>
    </ul>
</nav>

<div class="container mx-auto">
    @yield('content')
</div>
