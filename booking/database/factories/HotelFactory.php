<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // Название отеля
            'name' => $this->faker->company(),
            // Описание отеля
            'description' => $this->faker->realText(rand(50, 500)), // 50-500 символов
            // Ссылка на изображение
//            'poster_url' => $this->faker->imageUrl(),
//            'poster_url' => $this->faker->imageUrl(640, 480, null, true, 'Hotel'),
            'poster_url' => 'https://via.placeholder.com/640x480.png/005E82?text=Hotel_' . $this->faker->word, // 005E82 - цвет
            // Адрес
            'address' => $this->faker->address(),
            // Дата создания/обновления
            'created_at' => $this->faker->dateTimeInInterval('-5 years', '4 years'),
            'updated_at' => $this->faker->dateTimeInInterval('-1 years', '1 years')
        ];
    }
}
