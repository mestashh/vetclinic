<nav class="bg-gray-800 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <ul class="flex space-x-6">
            <li><a href="{{ route('home') }}" class="hover:text-blue-400">Главная</a></li>
            <li><a href="{{ route('clients.index') }}" class="hover:text-blue-400">Клиенты</a></li>
            <li><a href="{{ route('appointments.index') }}" class="hover:text-blue-400">Приёмы</a></li>
            <li><a href="{{ route('pets.index') }}" class="hover:text-blue-400">Питомцы</a></li>
            <li><a href="{{ route('services.index') }}" class="hover:text-blue-400">Услуги</a></li>
            <li><a href="{{ route('veterinarians.index') }}" class="hover:text-blue-400">Ветеринары</a></li>
        </ul>
    </div>
</nav>
