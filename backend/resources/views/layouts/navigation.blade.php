@vite(['src/app.css', 'src/app.js'])
<nav style="background-color:#2D3748;padding:1rem; color:white;  font-size:1.125rem;">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <ul style="display:flex; flex-direction:row; gap:1rem; list-style:none; margin:0; padding:0;">
            @auth
                @if(Auth::user()->role == 'client')
                    <li><a href="{{ url('/') }}"        class="text-white hover:text-gray-300">Главная</a></li>
                    <li><a href="{{ route('about') }}"        class="text-white hover:text-gray-300">Обо мне</a></li>
                    <li><a href="{{ route('my-appointments') }}" class="text-white hover:text-gray-300">Мои записи</a></li>
                    <li><a href="{{ route('news') }}" class="text-white hover:text-gray-300">Новости клиники</a></li>
                @endif
                @if(Auth::user()->role == 'vet')
                    <li><a href="{{ route('appointments') }}" class="text-white hover:text-gray-300">Приёмы</a></li>
                        <li><a href="{{ route('services') }}"    class="text-white hover:text-gray-300">Услуги</a></li>
                @endif
                @if(Auth::user()->role == 'admin')
                        <li><a href="{{ route('users') }}"        class="text-white hover:text-gray-300">Клиенты</a></li>
                        <li><a href="{{ route('appointments') }}" class="text-white hover:text-gray-300">Приёмы</a></li>
                        <li><a href="{{ route('pets') }}"        class="text-white hover:text-gray-300">Питомцы</a></li>
                @endif
                    @if(Auth::user()->role == 'superadmin')
                        <li><a href="{{ url('/') }}"        class="text-white hover:text-gray-300">Главная</a></li>
                        <li><a href="{{ route('users') }}"        class="text-white hover:text-gray-300">Клиенты</a></li>
                        <li><a href="{{ route('pets') }}"        class="text-white hover:text-gray-300">Питомцы</a></li>
                        <li><a href="{{ route('appointments') }}" class="text-white hover:text-gray-300">Приёмы</a></li>
                        <li><a href="{{ route('veterinarians') }}" class="text-white hover:text-gray-300">Ветеринары</a></li>
                        <li><a href="{{ route('services') }}"    class="text-white hover:text-gray-300">Услуги</a></li>
                    @endif
            @endauth
        </ul>

        {{-- правый блок: имя + кнопка --}}
        <div class="flex items-center space-x-4">
            @auth
                <span >{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded">
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
