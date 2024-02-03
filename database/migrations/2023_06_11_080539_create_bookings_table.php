<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            // Поля
            $table->id()->comment('Уникальный идентификатор брони');
            $table->unsignedBigInteger('room_id')->comment('Id номера');
            $table->unsignedBigInteger('user_id')->comment('Id пользователя');
            $table->date('started_at')->comment('Дата заезда');
            $table->date('finished_at')->comment('Дата выезда');
            $table->unsignedInteger('days')->comment('Количество дней');
            $table->decimal('price', 8, 2)->comment('Цена');
            $table->timestamp('created_at')->nullable()->comment('Дата создания записи');
            $table->timestamp('updated_at')->nullable()->comment('Дата обновления записи');
            // Уникальные индексы
            $table->unique('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
