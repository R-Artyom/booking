<?php

namespace Database\Seeders;

use App\Models\FacilityHotel;
use Illuminate\Database\Seeder;

class FacilityHotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Заполнение таблицы 'facility_hotel' базы данных тестовыми значениями
        FacilityHotel::factory(300)->create();
    }
}
