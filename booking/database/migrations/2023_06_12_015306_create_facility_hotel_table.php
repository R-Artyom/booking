<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityHotelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility_hotel', function (Blueprint $table) {
            // Поля
            $table->id()->comment('Уникальный идентификатор строки');
            $table->unsignedBigInteger('facility_id')->comment('Id удобства');
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
        Schema::dropIfExists('facility_hotel');
    }
}
