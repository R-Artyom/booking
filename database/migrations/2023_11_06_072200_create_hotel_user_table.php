<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_user', function (Blueprint $table) {
            // * Поля
            $table->id()->comment('Отели менеджеров');
            $table->unsignedBigInteger('user_id')->comment('Пользователь');
            $table->unsignedBigInteger('hotel_id')->comment('Отель');
            $table->timestamp('created_at')->nullable()->comment('Дата создания записи');
            $table->timestamp('updated_at')->nullable()->comment('Дата обновления записи');

            // * Простые индексы
            $table->index('user_id');
            $table->index('hotel_id');

            // * Уникальные индексы
            $table->unique(['user_id', 'hotel_id']); // составой из двух

            // * Внешние ключи:
            // Ссылка на столбец id в таблице users
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            // Ссылка на столбец id в таблице hotels
            $table->foreign('hotel_id')
                ->references('id')->on('hotels')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_user');
    }
}
