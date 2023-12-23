<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class FacilityRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // Id удобства
            'facility_id' => rand(11, 20),
            // Id номера
            'room_id' => Room::get()->random()->id,
            // Дата создания/обновления
            'created_at' => $this->faker->dateTimeInInterval('-5 years', '4 years'),
            'updated_at' => $this->faker->dateTimeInInterval('-1 years', '1 years')
        ];
    }
}
