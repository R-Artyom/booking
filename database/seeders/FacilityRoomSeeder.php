<?php

namespace Database\Seeders;

use App\Models\FacilityRoom;
use Illuminate\Database\Seeder;

class FacilityRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Заполнение таблицы 'facility_hotel' базы данных тестовыми значениями
        FacilityRoom::factory(500)->create();
    }
}
