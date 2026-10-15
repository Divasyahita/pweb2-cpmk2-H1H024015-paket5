<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'vehicle_id' => Vehicle::factory(),
            'tanggal_masuk' => fake()->dateTimeBetween(
                '-1 year',
                'now'
            )->format('Y-m-d'),
            'keluhan' => fake()->randomElement([
                'Mesin sulit dinyalakan',
                'Oli perlu diganti',
                'Rem kurang pakem',
                'Ban perlu diperiksa',
                'Mesin terasa kurang bertenaga',
                'Lampu kendaraan tidak menyala',
                'Aki mulai melemah',
                'Suara mesin tidak normal',
                'Servis rutin kendaraan',
                'Rantai kendaraan kendur',
            ]),
            'biaya_jasa' => fake()->numberBetween(25000, 200000),
            'biaya_sparepart' => fake()->numberBetween(0, 500000),
            'status' => fake()->randomElement([
                'menunggu',
                'dikerjakan',
                'selesai',
                'diambil',
            ]),
        ];
    }
}