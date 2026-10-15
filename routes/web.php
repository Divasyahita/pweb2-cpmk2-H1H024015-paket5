
<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::livewire('/pelanggan', 'customers.customer-index')
    ->name('customers.index');

Route::livewire('/kendaraan', 'vehicles.vehicle-index')
    ->name('vehicles.index');

Route::livewire('/servis', 'services.service-index')
    ->name('services.index');