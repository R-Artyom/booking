<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkToFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            // * Простые индексы
            $table->index('hotel_id', 'feedback_hotel_idx');
            $table->index('user_id', 'feedback_user_idx');

            // * Внешние ключи:
            // Ссылка на столбец id в таблице hotels
            $table->foreign('hotel_id', 'feedback_hotel_fk')
                ->references('id')->on('hotels')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            // Ссылка на столбец id в таблице users
            $table->foreign('user_id', 'feedback_user_fk')
                ->references('id')->on('users')
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
        Schema::table('feedbacks', function (Blueprint $table) {
            // Сначала удаление внешних ключей
            $table->dropForeign('feedback_hotel_fk');
            $table->dropForeign('feedback_user_fk');
            // Затем индексов
            $table->dropIndex('feedback_hotel_idx');
            $table->dropIndex('feedback_user_idx');
        });
    }
}
