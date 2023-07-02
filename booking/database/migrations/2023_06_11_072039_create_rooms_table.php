<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            // Поля
            $table->id()->comment('Уникальный идентификатор номера отеля');
            $table->string('title', 100)->comment('Название номера');
            $table->text('description')->nullable()->comment('Описание номера');
            $table->string('poster_url', 100)->nullable()->comment('Ссылка на изображение');
            $table->decimal('floor_area', 8, 2)->comment('Площадь номера');
            $table->string('type', 100)->comment('Тип');
            $table->decimal('price', 8, 2)->comment('Цена');
            $table->unsignedBigInteger('hotel_id')->comment('Id отеля');
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
        Schema::dropIfExists('rooms');
    }
}
