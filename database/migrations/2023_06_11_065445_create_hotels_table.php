<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            // Поля
            $table->id()->comment('Уникальный идентификатор отеля');
            $table->string('name', 100)->comment('Название отеля');
            $table->text('description')->nullable()->comment('Описание отеля');
            $table->string('poster_url', 100)->nullable()->comment('Ссылка на изображение');
            $table->string('address', 500)->comment('Адрес');
            $table->decimal('rating', 2, 1)->unsigned()->nullable()->default(null)->comment('Рейтинг');
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
        Schema::dropIfExists('hotels');
    }
}
