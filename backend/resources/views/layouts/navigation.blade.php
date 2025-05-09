@vite(['src/app.css', 'src/app.js'])

<nav style="background-color:#2D3748;padding:1rem; color:white; font-size:1.125rem;">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        {{-- Левый блок: ссылки --}}
        <ul style="display:flex; flex-direction:row; gap:1rem; list-style:none; margin:0; padding:0;">
            @guest
                <a href="{{ route('home') }}" class="text-white hover:text-gray-300 px-3 py-2 rounded-md text-sm font-medium">Главная</a>
                <a href="{{ route('news') }}" class="text-white hover:text-gray-300 px-3 py-2 rounded-md text-sm font-medium">Новости клиники</a>
            @endguest
            @auth
                @if(Auth::user()->role == 'client')
                    <li><a href="{{ route('about') }}"            class="text-white hover:text-gray-300">Обо мне</a></li>
                    <li><a href="{{ route('my-appointments') }}"  class="text-white hover:text-gray-300">Записаться на прием</a></li>
                        <li><a href="{{ route('my-past-appointments') }}" class="text-white hover:text-gray-300">История приёмов</a></li>
                    <li><a href="{{ route('news') }}"             class="text-white hover:text-gray-300">Новости клиники</a></li>
                @endif

                @if(Auth::user()->role == 'vet')
                    <li><a href="{{ route('appointments') }}"     class="text-white hover:text-gray-300">Приёмы</a></li>
                    <li><a href="{{ route('services') }}"         class="text-white hover:text-gray-300">Услуги</a></li>
                    <li><a href="{{ route('appointments.select') }}" class="text-white hover:text-gray-300">Проведение приёма</a></li>

                    @endif

                @if(Auth::user()->role == 'admin')
                    <li><a href="{{ route('users') }}"            class="text-white hover:text-gray-300">Клиенты</a></li>
                    <li><a href="{{ route('appointments') }}"     class="text-white hover:text-gray-300">Приёмы</a></li>
                    <li><a href="{{ route('pets') }}"             class="text-white hover:text-gray-300">Питомцы</a></li>
                @endif

                @if(Auth::user()->role == 'superadmin')
                    <li><a href="{{ route('users') }}"            class="text-white hover:text-gray-300">Клиенты</a></li>
                    <li><a href="{{ route('pets') }}"             class="text-white hover:text-gray-300">Питомцы</a></li>
                    <li><a href="{{ route('appointments') }}"     class="text-white hover:text-gray-300">Приёмы</a></li>
                    <li><a href="{{ route('veterinarians') }}"    class="text-white hover:text-gray-300">Ветеринары</a></li>
                    <li><a href="{{ route('services') }}"         class="text-white hover:text-gray-300">Услуги</a></li>
                    <li><a href="{{ route('change-roles') }}"     class="text-white hover:text-gray-300">Управление ролями</a></li>
                    <li><a href="{{ route('news') }}"             class="text-white hover:text-gray-300">Новости клиники</a></li>
                @endif
            @endauth
        </ul>

        <div style="display:flex; align-items:center; gap:1rem;">
            @auth
                <span>{{ Auth::user()->first_name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            style="background-color:#dc2626; color:white; padding: 0.4rem 0.75rem;
                                   border:none; border-radius:4px; font-weight:500; cursor:pointer;">
                        Выйти
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded">
                    Вход
                </a>
            @endauth
        </div>
    </div>
</nav>
