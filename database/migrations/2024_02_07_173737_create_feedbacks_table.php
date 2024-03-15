<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            // Поля
            $table->id()->comment('Отзыв');
            $table->unsignedBigInteger('hotel_id')->comment('Отель');
            $table->unsignedBigInteger('user_id')->comment('Пользователь');
            $table->string('text', 5000)->comment('Текст');
            $table->tinyInteger('is_active')->nullable()->default(null)->comment('Признак активности');
            $table->tinyInteger('rating')->unsigned()->nullable()->default(null)->comment('Оценка');
            $table->timestamp('created_at')->nullable()->comment('Создано');
            $table->timestamp('updated_at')->nullable()->comment('Обновлено');
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
        Schema::dropIfExists('feedbacks');
    }
}
