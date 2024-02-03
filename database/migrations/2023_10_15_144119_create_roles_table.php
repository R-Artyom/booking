<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            // * Поля
            $table->string('name', 50)->comment('Роль');
            $table->string('description', 50)->comment('Описание');
            $table->timestamp('created_at')->nullable()->comment('Дата создания записи');
            $table->timestamp('updated_at')->nullable()->comment('Дата обновления записи');

            // * Первичный ключ
            $table->primary('name');
        });

        // Создание ролей
        Role::create([
            'name' => 'admin',
            'description' => 'Администратор',
        ]);
        Role::create([
            'name' => 'guest',
            'description' => 'Гость',
        ]);
        Role::create([
            'name' => 'manager',
            'description' => 'Менеджер',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
