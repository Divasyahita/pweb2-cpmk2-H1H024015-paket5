<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake('id_ID')->name(),
            'no_hp' => '08' . fake()->numerify('##########'),
            'alamat' => fake('id_ID')->address(),
        ];
    }
}