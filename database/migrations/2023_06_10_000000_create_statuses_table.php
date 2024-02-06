<?php

use App\Models\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            // Поля
            $table->id()->comment('Статусы приложения');
            $table->string('name', 100)->comment('Название');
            $table->timestamp('created_at')->nullable()->comment('Дата создания записи');
            $table->timestamp('updated_at')->nullable()->comment('Дата обновления записи');
            // Уникальные индексы
            $table->unique('id');
        });

        // Заполнение таблицы начальными значениями
        $statuses = [
            ['name' => 'Создан'],
            ['name' => 'Активен'],
            ['name' => 'Завершен'],
        ];
        // Добавление временных меток
        $facilities = array_map(function($value) {
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['updated_at'] = date('Y-m-d H:i:s');
            return $value;
        }, $statuses);

        Status::query()->insert($facilities);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
}
