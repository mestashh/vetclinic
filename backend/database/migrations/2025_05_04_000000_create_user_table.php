<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');               // Имя
            $table->string('middle_name')->nullable();  // Отчество
            $table->string('last_name');                // Фамилия
            $table->string('email')->unique();          // Почта
            $table->string('phone')->nullable();        // Телефон
            $table->string('address')->nullable();      // Адрес
            $table->string('role')->default('client');  // Роль
            $table->string('password');                 // Пароль (хеш)
            $table->rememberToken();                    // Токен "запомнить меня"
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
