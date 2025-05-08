<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'first_name' => 'Иван',
            'middle_name' => null,
            'last_name' => 'Клиент',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        User::create([
            'first_name' => 'Анна',
            'middle_name' => null,
            'last_name' => 'Врач',
            'email' => 'vet@example.com',
            'password' => Hash::make('password'),
            'role' => 'vet',
        ]);

        User::create([
            'first_name' => 'Пётр',
            'middle_name' => null,
            'last_name' => 'Админ',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'first_name' => 'Елена',
            'middle_name' => null,
            'last_name' => 'Суперадмин',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);
    }
}
