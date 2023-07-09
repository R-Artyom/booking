<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

class FacilityHotelFactory extends Factory
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
            'facility_id' => Facility::get()->random()->id,
            // Id отеля
            'hotel_id' => Hotel::get()->random()->id,
            // Дата создания/обновления
            'created_at' => $this->faker->dateTimeInInterval('-5 years', '4 years'),
            'updated_at' => $this->faker->dateTimeInInterval('-1 years', '1 years')
        ];
    }
}
