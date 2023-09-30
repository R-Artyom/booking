<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // Название номера
            'name' => $this->faker->realText(rand(10, 100)), // 10-100 символов
            // Описание номера
            'description' => $this->faker->realText(rand(50, 500)),
            // Ссылка на изображение
            'poster_url' => $this->faker->imageUrl(),
            // Площадь номера (32.00 ... 100.00 м2)
            'floor_area' => random_int(32, 100) . '.' . random_int(0, 9),
            // Тип
            'type' => $this->faker->realText(rand(10, 100)), // 10-100 символов
            // Цена (700.00 ... 20000.00 р)
            'price' => random_int(700, 20000) . '.' . random_int(0, 9),
            // Id отеля
            'hotel_id' => Hotel::get()->random()->id,
            // Дата создания/обновления
            'created_at' => $this->faker->dateTimeInInterval('-5 years', '4 years'),
            'updated_at' => $this->faker->dateTimeInInterval('-1 years', '1 years')
        ];
    }
}
