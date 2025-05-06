<nav style="background-color:#2D3748; color:white; padding:1rem;">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <ul style="display:flex; flex-direction:row; gap:1rem; list-style:none; margin:0; padding:0;">
            <li><a href="{{ route('home') }}" style="text-decoration:none; color:white;">Главная</a></li>
            <li><a href="{{ route('clients') }}" style="text-decoration:none; color:white;">Клиенты</a></li>
            <li><a href="{{ route('appointments') }}" style="text-decoration:none; color:white;">Приёмы</a></li>
            <li><a href="{{ route('pets') }}" style="text-decoration:none; color:white;">Питомцы</a></li>
            <li><a href="{{ route('services') }}" style="text-decoration:none; color:white;">Услуги</a></li>
            <li><a href="{{ route('veterinarians') }}" style="text-decoration:none; color:white;">Ветеринары</a></li>
        </ul>

        <div style="display:flex; align-items:center;">
            @auth
                <span style="margin-right:1rem;">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="padding:0.5rem 1rem; background:#E53E3E; color:white; border:none; cursor:pointer;">
                        Выйти
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" style="padding:0.5rem 1rem; background:#48BB78; color:white; text-decoration:none;">
                    Войти
                </a>
            @endauth
        </div>
    </div>
</nav>

<div class="container mx-auto">
    @yield('content')
</div>
