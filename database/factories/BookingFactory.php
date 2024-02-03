<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Дата заезда
        $startedAt = $this->faker
            // В промежутке 5 лет до текущего времени + 6 месяцев от текущего
            ->dateTimeInInterval('-5 years', '5 years + 6 month')
            // Время заезда всегда 12:00
            ->setTime(12, 0)
            ->format('Y-m-d H:i:s');
        // Количество дней - от 1 до 14 (2 недели)
        $dayNumber = rand(1, 14);
        // Дата выезда в секундах, приведённая к 10:00 (86400 - сутки, 7200 - 2 часа)
        $finishedAt = strtotime($startedAt) + $dayNumber * 86400 - 7200;
        $finishedAt = date('Y-m-d H:i:s', $finishedAt);

        return [
            // Id номера
            'room_id' => Room::get()->random()->id,
            // Id пользователя
            'user_id' => User::get()->random()->id,
            // Дата заезда
            'started_at' => $startedAt,
            // Дата выезда
            'finished_at' => $finishedAt,
            // Количество дней
            'days' => $dayNumber,
            // Цена (700.00 ... 20000.00 р) - зависит от количества дней
            'price' => random_int(700, 20000) * $dayNumber . '.' . random_int(0, 9),
            // Дата создания/обновления
            'created_at' => $this->faker->dateTimeInInterval('-5 years', '4 years'),
            'updated_at' => $this->faker->dateTimeInInterval('-1 years', '1 years')
        ];
    }
}
