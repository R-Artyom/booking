<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // * Простые индексы
            $table->index('room_id', 'booking_room_idx');
            $table->index('user_id', 'booking_user_idx');

            // * Внешние ключи:
            // Ссылка на столбец id в таблице rooms
            $table->foreign('room_id', 'booking_room_fk')
                ->references('id')->on('rooms')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            // Ссылка на столбец id в таблице users
            $table->foreign('user_id', 'booking_user_fk')
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
        Schema::table('bookings', function (Blueprint $table) {
            // Сначала удаление внешних ключей
            $table->dropForeign('booking_room_fk');
            $table->dropForeign('booking_user_fk');
            // Затем индексов
            $table->dropIndex('booking_room_idx');
            $table->dropIndex('booking_user_idx');
        });
    }
}
