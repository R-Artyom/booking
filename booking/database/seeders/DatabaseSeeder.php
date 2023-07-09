<?php

namespace Database\Seeders;

use Database\Factories\FacilityHotelFactory;
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
        // Таблица 'users'
        $this->call(UserSeeder::class);
        // Таблица 'hotels'
        $this->call(HotelSeeder::class);
        // Таблица 'rooms'
        $this->call(RoomSeeder::class);
        // Таблица 'bookings'
        $this->call(BookingSeeder::class);
        // Таблица 'facilities'
        $this->call(FacilitySeeder::class);
        // Таблица 'facility_hotel'
        $this->call(FacilityHotelSeeder::class);
        // Таблица 'facility_room'
        $this->call(FacilityRoomSeeder::class);
    }
}
