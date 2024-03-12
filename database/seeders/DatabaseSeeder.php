<?php

namespace Database\Seeders;

use App\Models\Feedback;
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

        // * Заполнение базы тестовыми значениями:
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
                $randomHotelIds = array_unique([rand(1, 100), rand(1, 100), rand(1, 100)]);
                foreach ($randomHotelIds as $randomHotelId) {
                    HotelUser::factory()->create([
                        'user_id' => $user->id,
                        'hotel_id' => $randomHotelId,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->created_at,
                    ]);
                }
            }
        }

        // Таблица 'feedbacks' (отзывы)
        foreach ($users as $user) {
            // Возможные значения флага активности (0 - отклонён, 1 - одобрен, null - на проверке)
            $isActive = [0, 1, null];
            // Если у пользователя есть завершённые бронирования
            if ($user->bookings->containsStrict('status_id', config('status.Завершен'))) {
                foreach ($user->bookings as $booking) {
                    // Создание отзыва только для отеля с завершённым бронированием
                    if ($booking->status_id === config('status.Завершен')) {
                        Feedback::factory()->create([
                            'hotel_id' => $booking->room->hotel_id,
                            'user_id' => $user->id,
                            'is_active' => $isActive[random_int(0, 2)],
                            'rating' => random_int(1, 5),
                            'created_at' => $user->created_at,
                            'updated_at' => $user->created_at,
                        ]);
                    }
                }
            }
        }

        // * Расчёт рейтинга отелей
        // Данные о всех ОДОБРЕННЫХ отзывах
        $feedbacksCollect = Feedback::query()
            ->where('is_active', 1)
            ->get();
        // Отзывы по отелю
        $feedbacksByHotelId = [];
        foreach ($feedbacksCollect as $feedback) {
            $feedbacksByHotelId[$feedback->hotel_id][] = $feedback->rating;
        }
        // Итоговый рейтинг отелей и количество оценок
        foreach ($hotels as $hotel) {
            // Если у отеля есть отзывы
            if (isset($feedbacksByHotelId[$hotel->id])) {
                // Количество оценок
                $hotel->feedback_quantity = count($feedbacksByHotelId[$hotel->id]);
                // Рейтинг отеля
                $hotel->rating = array_sum($feedbacksByHotelId[$hotel->id]) / $hotel->feedback_quantity;
            // Если у отеля отзывов нет
            } else {
                $hotel->feedback_quantity = null;
                $hotel->rating = null;
            }
            // Если были какие-либо изменения в модели (атрибуты, которые были изменены с момента последней синхронизации (attributes VS original))
            if ($hotel->isDirty()) {
                // Обновление записи в таблице 'hotels'
                $hotel->save();
            }
        }
    }
}
