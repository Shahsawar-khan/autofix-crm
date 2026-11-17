<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Lead;
use App\Enums\LeadStatus;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $leadsData = [
            // --- NEW (3) ---
            ['name' => 'Ali Khan', 'phone' => '+923001234567', 'make' => 'Honda', 'model' => 'Civic', 'year' => 2018, 'plate' => 'LEB-1234', 'job' => 'Brake Pad Replacement', 'status' => LeadStatus::NEW],
            ['name' => 'Zainab Malik', 'phone' => '+923129876543', 'make' => 'Suzuki', 'model' => 'Swift', 'year' => 2021, 'plate' => 'KHI-5566', 'job' => 'Routine Maintenance', 'status' => LeadStatus::NEW],
            ['name' => 'Faisal Shah', 'phone' => '+923331112233', 'make' => 'Toyota', 'model' => 'Yaris', 'year' => 2019, 'plate' => 'ISB-8899', 'job' => 'AC Not Cooling', 'status' => LeadStatus::NEW],

            // --- QUOTED (3) ---
            ['name' => 'Hamza Ali', 'phone' => '+923451234567', 'make' => 'Honda', 'model' => 'City', 'year' => 2017, 'plate' => 'LHR-1010', 'job' => 'Clutch Plate Replacement', 'status' => LeadStatus::QUOTED],
            ['name' => 'Ayesha Siddiqui', 'phone' => '+923215556677', 'make' => 'Kia', 'model' => 'Picanto', 'year' => 2020, 'plate' => 'KHI-2020', 'job' => 'Suspension Check', 'status' => LeadStatus::QUOTED],
            ['name' => 'Omer Farooq', 'phone' => '+923338889900', 'make' => 'Toyota', 'model' => 'Fortuner', 'year' => 2022, 'plate' => 'ISB-3030', 'job' => 'Full Body Paint', 'status' => LeadStatus::QUOTED],

            // --- BOOKED (3) ---
            ['name' => 'Nida Yasir', 'phone' => '+923024567890', 'make' => 'Suzuki', 'model' => 'Cultus', 'year' => 2016, 'plate' => 'KHI-4040', 'job' => 'Engine Tuning', 'status' => LeadStatus::BOOKED],
            ['name' => 'Tariq Mahmood', 'phone' => '+923147778899', 'make' => 'Honda', 'model' => 'BR-V', 'year' => 2018, 'plate' => 'LHR-5050', 'job' => 'Timing Belt Change', 'status' => LeadStatus::BOOKED],
            ['name' => 'Sana Khan', 'phone' => '+923351234321', 'make' => 'Hyundai', 'model' => 'Tucson', 'year' => 2021, 'plate' => 'ISB-6060', 'job' => 'Battery Replacement', 'status' => LeadStatus::BOOKED],

            // --- IN REPAIR (3) ---
            ['name' => 'Sarah Ahmed', 'phone' => '+923219876543', 'make' => 'Toyota', 'model' => 'Corolla', 'year' => 2020, 'plate' => 'ABC-5678', 'job' => 'Synthetic Oil Change', 'status' => LeadStatus::IN_REPAIR],
            ['name' => 'Usman Ghani', 'phone' => '+923431112233', 'make' => 'Suzuki', 'model' => 'Alto', 'year' => 2015, 'plate' => 'KHI-7070', 'job' => 'Wheel Alignment', 'status' => LeadStatus::IN_REPAIR],
            ['name' => 'Bilal Butt', 'phone' => '+923209998877', 'make' => 'Honda', 'model' => 'Civic', 'year' => 2019, 'plate' => 'LHR-8080', 'job' => 'Transmission Fluid Flush', 'status' => LeadStatus::IN_REPAIR],

            // --- COMPLETED (3) ---
            ['name' => 'Bilal Sheikh', 'phone' => '+923335556677', 'make' => 'Suzuki', 'model' => 'Alto', 'year' => 2015, 'plate' => 'XYZ-9012', 'job' => 'Engine Diagnostics', 'status' => LeadStatus::COMPLETED],
            ['name' => 'Kashif Ali', 'phone' => '+923125554433', 'make' => 'Toyota', 'model' => 'Hilux', 'year' => 2020, 'plate' => 'ISB-9090', 'job' => 'Turbo Replacement', 'status' => LeadStatus::COMPLETED],
            ['name' => 'Mehwish Hayat', 'phone' => '+923467778811', 'make' => 'Kia', 'model' => 'Sportage', 'year' => 2021, 'plate' => 'KHI-1122', 'job' => 'Brake Fluid Flush', 'status' => LeadStatus::COMPLETED],
        ];

        foreach ($leadsData as $data) {
            // Contact create ya fetch karein
            $contact = Contact::firstOrCreate(
                ['phone' => $data['phone']],
                ['name' => $data['name']]
            );

            // Lead create karein
            $contact->leads()->create([
                'make' => $data['make'],
                'model' => $data['model'],
                'year' => $data['year'],
                'license_plate' => $data['plate'],
                'service_needed' => $data['job'],
                'status' => $data['status'],
            ]);
        }
    }
}