<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            // Поля
            $table->id()->comment('Уникальный идентификатор пользователя');
            $table->string('name', 100)->comment('ФИО пользователя');
            $table->string('email', 100)->comment('email пользователя');
            $table->timestamp('email_verified_at')->nullable()->comment('Дата подтверждния email-а пользователя');
            $table->string('password', 255)->comment('Пароль пользователя');
            $table->rememberToken()->comment('Токен'); // $table->string('remember_token', 100)->nullable()->comment('Токен');
            $table->timestamp('created_at')->nullable()->comment('Дата создания записи');
            $table->timestamp('updated_at')->nullable()->comment('Дата обновления записи');

            // Уникальные индексы
            $table->unique('id');
            $table->unique('email');
        });

        // Создание первого администратора
        User::create([
            'name' => 'admin',
            'email' => 'admin@mail.ru',
            'password' => bcrypt('admin'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
