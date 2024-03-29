<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Доступные роли
        $names = ['guest', 'manager'];

        return [
            // Роль
            'name' => array_rand(array_flip($names)),
        ];
    }
}
