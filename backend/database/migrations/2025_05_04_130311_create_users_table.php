<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // Имя пользователя
            $table->string('email')->unique();               // Почта
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');                      // Хеш пароля
            $table->rememberToken();                         // Токен «запомнить меня»
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
