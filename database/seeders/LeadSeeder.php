<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        Lead::create(['customer_name' => 'Ali Khan', 'phone' => '03001234567', 'vehicle_info' => 'Honda Civic 2018', 'service_needed' => 'Brake Pad Replacement']);
        Lead::create(['customer_name' => 'Sarah Ahmed', 'phone' => '03219876543', 'vehicle_info' => 'Toyota Corolla 2020', 'service_needed' => 'Synthetic Oil Change']);
        Lead::create(['customer_name' => 'Bilal Sheikh', 'phone' => '03335556677', 'vehicle_info' => 'Suzuki Alto 2015', 'service_needed' => 'Engine Diagnostics']);
    }
}