<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Заполнение таблицы 'hotels' тестовыми значениями
        $this->call(HotelSeeder::class);
        // Заполнение таблицы 'rooms' тестовыми значениями
        $this->call(RoomSeeder::class);
    }
}
