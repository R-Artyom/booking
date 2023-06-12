<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkToRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            // * Простые индексы
            $table->index('hotel_id', 'room_hotel_idx');

            // * Внешние ключи:
            // Ссылка на столбец id в таблице hotels
            $table->foreign('hotel_id', 'room_hotel_fk')
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
        Schema::table('rooms', function (Blueprint $table) {
            // Сначала удаление внешних ключей
            $table->dropForeign('room_hotel_fk');
            // Затем индексов
            $table->dropIndex('room_hotel_idx');
        });
    }
}
