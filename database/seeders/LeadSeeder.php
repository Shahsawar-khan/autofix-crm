<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Lead;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ali Khan ka Contact banayein, phir uski Gaari ka Lead
        $contact1 = Contact::create([
            'name' => 'Ali Khan',
            'phone' => '+923001234567',
        ]);
        
        $contact1->leads()->create([
            'make' => 'Honda',
            'model' => 'Civic',
            'year' => 2018,
            'license_plate' => 'LEB-1234',
            'service_needed' => 'Brake Pad Replacement',
            'status' => 'New',
        ]);

        // 2. Sarah Ahmed ka Contact banayein, phir uski Gaari ka Lead
        $contact2 = Contact::create([
            'name' => 'Sarah Ahmed',
            'phone' => '+923219876543',
        ]);

        $contact2->leads()->create([
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2020,
            'license_plate' => 'ABC-5678',
            'service_needed' => 'Synthetic Oil Change',
            'status' => 'In Repair', // Pipeline ke liye alag status
        ]);

        // 3. Bilal Sheikh ka Contact banayein, phir uski Gaari ka Lead
        $contact3 = Contact::create([
            'name' => 'Bilal Sheikh',
            'phone' => '+923335556677',
        ]);

        $contact3->leads()->create([
            'make' => 'Suzuki',
            'model' => 'Alto',
            'year' => 2015,
            'license_plate' => 'XYZ-9012',
            'service_needed' => 'Engine Diagnostics',
            'status' => 'Completed', // Pipeline ke liye alag status
        ]);
    }
}