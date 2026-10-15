<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    public function definition(): array
    {
        $merek = fake()->randomElement([
            'Honda',
            'Yamaha',
            'Toyota',
            'Suzuki',
            'Daihatsu',
            'Mitsubishi',
        ]);

        $tipeKendaraan = [
            'Honda' => ['Beat', 'Vario', 'CB150R'],
            'Yamaha' => ['Mio', 'NMAX', 'Aerox'],
            'Toyota' => ['Avanza', 'Agya', 'Innova'],
            'Suzuki' => ['Address', 'Nex II', 'Ertiga'],
            'Daihatsu' => ['Ayla', 'Xenia', 'Terios'],
            'Mitsubishi' => ['Xpander', 'Pajero', 'L300'],
        ];

        return [
            'customer_id' => Customer::factory(),
            'plat_nomor' => strtoupper(
                fake()->unique()->bothify('?? #### ??')
            ),
            'merek' => $merek,
            'tipe' => fake()->randomElement($tipeKendaraan[$merek]),
            'tahun' => fake()->numberBetween(2010, (int) date('Y')),
        ];
    }
}