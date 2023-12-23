<?php

use App\Models\Facility;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facilities', function (Blueprint $table) {
            // Поля
            $table->id()->comment('Уникальный идентификатор удобства');
            $table->string('name', 100)->comment('Название удобства');
            $table->timestamp('created_at')->nullable()->comment('Дата создания записи');
            $table->timestamp('updated_at')->nullable()->comment('Дата обновления записи');
            // Уникальные индексы
            $table->unique('id');
        });

        // Заполнение таблицы начальными значениями
        $facilities = [
            // Удобства отеля
            ['name' => 'Бар'],
            ['name' => 'Бассейн'],
            ['name' => 'Бесплатный автрак'],
            ['name' => 'Бильярд'],
            ['name' => 'Гольф'],
            ['name' => 'Парковка'],
            ['name' => 'Спортзал'],
            ['name' => 'Теннис'],
            ['name' => 'Торговый автомат'],
            ['name' => 'Шведский стол'],
            // Удобства номера
            ['name' => 'Кондиционер'],
            ['name' => 'Кухонные принадлежности'],
            ['name' => 'Микроволновка'],
            ['name' => 'Полотенца'],
            ['name' => 'Стиральная машина'],
            ['name' => 'Телевизор'],
            ['name' => 'Утюг'],
            ['name' => 'Фен'],
            ['name' => 'Холодильник'],
            ['name' => 'Wi-Fi'],
        ];
        foreach ($facilities as $facility) {
            $facility['created_at'] = date('Y-m-d H:i:s');
            $facility['updated_at'] = date('Y-m-d H:i:s');
        }
        Facility::insert($facilities);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facilities');
    }
}
