<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\HotelUser;
use App\Models\RoleUser;
use App\Models\Room;
use App\Models\User;
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
        // * Запрет отправки email уведомлений на время записи тестовых данных
        config(['enable.userEmailNotifications' => false]);

        // * Заполнение тестовыми значениями:
        // Таблица 'users' (Пользователи)
        $users = User::factory(100)->create();

        // Таблица 'hotels' (Отели)
        $hotels = Hotel::factory(100)->create();

        // Таблица 'rooms' (Номера)
        // Генерирование 100 шт комнат по умолчанию - со случайным отелем
        $this->call(RoomSeeder::class);
        // Геренирование для каждого отеля одного номера, чтобы не было отелей без номеров
        foreach ($hotels as $hotel) {
            Room::factory()->create([
                'hotel_id' => $hotel->id,
            ]);
        }

        // Таблица 'bookings' (Бронирования)
        $this->call(BookingSeeder::class);

        // Таблица 'facility_hotel' (Удобства отелей)
        $this->call(FacilityHotelSeeder::class);

        // Таблица 'facility_room' (Удобства номеров)
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
