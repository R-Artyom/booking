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
        // * Заполнение тестовыми значениями:
        // Таблица 'hotels'
        $this->call(HotelSeeder::class);
        // Таблица 'rooms'
        $this->call(RoomSeeder::class);
        // Таблица 'users'
        $this->call(UserSeeder::class);
        // Таблица 'bookings'
        $this->call(BookingSeeder::class);
    }
}
