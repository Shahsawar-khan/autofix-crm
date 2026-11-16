<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;

class LeadSeeder extends Seeder
{
        public function run(): void
    {
        Lead::create([
            'customer_name' => 'Ali Khan', 
            'phone' => '03001234567', 
            'make' => 'Honda', 
            'model' => 'Civic', 
            'year' => 2018, 
            'license_plate' => 'LEB-1234', 
            'service_needed' => 'Brake Pad Replacement'
        ]);
        Lead::create([
            'customer_name' => 'Sarah Ahmed', 
            'phone' => '03219876543', 
            'make' => 'Toyota', 
            'model' => 'Corolla', 
            'year' => 2020, 
            'license_plate' => 'ABC-5678', 
            'service_needed' => 'Synthetic Oil Change'
        ]);
        Lead::create([
            'customer_name' => 'Bilal Sheikh', 
            'phone' => '03335556677', 
            'make' => 'Suzuki', 
            'model' => 'Alto', 
            'year' => 2015, 
            'license_plate' => 'XYZ-9012', 
            'service_needed' => 'Engine Diagnostics'
        ]);
    }
}