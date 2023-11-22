<?php

use App\Models\RoleUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            // * Поля
            $table->id()->comment('Роли пользователей');
            $table->string('name', 50)->comment('Роль');
            $table->unsignedBigInteger('user_id')->comment('Id пользователя');
            $table->timestamp('created_at')->nullable()->comment('Дата создания записи');
            $table->timestamp('updated_at')->nullable()->comment('Дата обновления записи');

            // * Простые индексы
            $table->index('name');
            $table->index('user_id');

            // * Уникальные индексы
            // Примеч. - если у одного пользователя м.б. много ролей - то этот индекс надо убрать
            $table->unique('user_id');

            // * Внешние ключи:
            // Ссылка на столбец id в таблице roles
            $table->foreign('name')
                ->references('name')->on('roles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            // Ссылка на столбец id в таблице users
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // Назначение роли первому пользователю
        RoleUser::create([
            'name' => 'admin',
            'user_id' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
    }
}
