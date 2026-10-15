<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat 20 pelanggan
        Customer::factory()->count(20)->create();

        // Membuat 20 kendaraan milik pelanggan yang tersedia
        $customerIds = Customer::pluck('id')->all();

        Vehicle::factory()
            ->count(20)
            ->create([
                'customer_id' => fn () => fake()->randomElement($customerIds),
            ]);

        // Membuat 20 riwayat servis dari kendaraan yang tersedia
        $vehicleIds = Vehicle::pluck('id')->all();

        Service::factory()
            ->count(20)
            ->create([
                'vehicle_id' => fn () => fake()->randomElement($vehicleIds),
            ]);
    }
}