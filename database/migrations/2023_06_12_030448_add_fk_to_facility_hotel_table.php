<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkToFacilityHotelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facility_hotel', function (Blueprint $table) {
            // * Простые индексы
            $table->index('hotel_id', 'facility_hotel_hotel_idx');
            $table->index('facility_id', 'facility_hotel_facility_idx');

            // * Внешние ключи:
            // Ссылка на столбец id в таблице hotels
            $table->foreign('hotel_id', 'facility_hotel_hotel_fk')
                ->references('id')->on('hotels')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            // Ссылка на столбец id в таблице facilities
            $table->foreign('facility_id', 'facility_hotel_facility_fk')
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
        Schema::table('facility_hotel', function (Blueprint $table) {
            // Сначала удаление внешних ключей
            $table->dropForeign('facility_hotel_hotel_fk');
            $table->dropForeign('facility_hotel_facility_fk');
            // Затем индексов
            $table->dropIndex('facility_hotel_hotel_idx');
            $table->dropIndex('facility_hotel_facility_idx');
        });
    }
}
