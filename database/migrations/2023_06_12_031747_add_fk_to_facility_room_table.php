<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkToFacilityRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facility_room', function (Blueprint $table) {
            // * Простые индексы
            $table->index('room_id', 'facility_room_room_idx');
            $table->index('facility_id', 'facility_room_facility_idx');

            // * Внешние ключи:
            // Ссылка на столбец id в таблице rooms
            $table->foreign('room_id', 'facility_room_room_fk')
                ->references('id')->on('rooms')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            // Ссылка на столбец id в таблице facilities
            $table->foreign('facility_id', 'facility_room_facility_fk')
                ->references('id')->on('facilities')
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
        Schema::table('facility_room', function (Blueprint $table) {
            // Сначала удаление внешних ключей
            $table->dropForeign('facility_room_room_fk');
            $table->dropForeign('facility_room_facility_fk');
            // Затем индексов
            $table->dropIndex('facility_room_room_idx');
            $table->dropIndex('facility_room_facility_idx');
        });
    }
}
