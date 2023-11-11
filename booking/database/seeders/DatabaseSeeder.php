<?php

namespace Database\Seeders;

use App\Models\HotelUser;
use App\Models\RoleUser;
use App\Models\User;
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
        $users = User::factory(100)->create();
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
        // Таблица 'role_user' (Роли пользоваелей)
        foreach ($users as $user) {
            RoleUser::factory()->create([
                'user_id' => $user->id,
                'created_at' => $user->created_at,
                'updated_at' => $user->created_at,
            ]);
        }
        // Таблица 'hotel_user' (Отели админа и менеджера)
        foreach ($users as $user) {
            // Все роли юзера
            $roles = $user->roles->pluck('name')->toArray();
            // Добавить случайный отель
            if (!empty(array_intersect($roles, ['admin', 'manager']))) {
                $hotels = array_unique([rand(1, 100), rand(1, 100), rand(1, 100)]);
                foreach ($hotels as $hotel) {
                    HotelUser::factory()->create([
                        'user_id' => $user->id,
                        'hotel_id' => $hotel,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->created_at,
                    ]);
                }
            }
        }
    }
}
