<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\EmailTemplate;
use App\Enums\LeadStatus;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. REALISTIC EMAIL TEMPLATES (Task 4)
        // ==========================================
        EmailTemplate::create([
            'name' => 'Quote Approved',
            'subject' => 'Your Repair Quote is Approved',
            'body' => 'Hi {customer_name}, your quote for {vehicle} is approved. Total estimated cost: {amount}. We will notify you when it is ready.'
        ]);
        
        EmailTemplate::create([
            'name' => 'Car Ready for Pickup',
            'subject' => 'Your Vehicle is Ready!',
            'body' => 'Great news {customer_name}! Your {vehicle} (Plate: {plate}) is ready for pickup. Total bill: {amount}.'
        ]);

        EmailTemplate::create([
            'name' => 'Follow-up Call',
            'subject' => 'Checking in on your vehicle',
            'body' => 'Hi {customer_name}, we tried calling you regarding your {vehicle}. Please let us know when you are available.'
        ]);

        // ==========================================
        // 2. LEADS & CONTACTS (Task 5: Amounts Added)
        // ==========================================
        $leadsData = [
            // --- NEW (3) ---
            ['name' => 'Ali Khan', 'phone' => '+923001234567', 'make' => 'Honda', 'model' => 'Civic', 'year' => 2018, 'plate' => 'LEB-1234', 'job' => 'Brake Pad Replacement', 'status' => LeadStatus::NEW, 'amount' => 15000],
            ['name' => 'Zainab Malik', 'phone' => '+923129876543', 'make' => 'Suzuki', 'model' => 'Swift', 'year' => 2021, 'plate' => 'KHI-5566', 'job' => 'Routine Maintenance', 'status' => LeadStatus::NEW, 'amount' => 8000],
            ['name' => 'Faisal Shah', 'phone' => '+923331112233', 'make' => 'Toyota', 'model' => 'Yaris', 'year' => 2019, 'plate' => 'ISB-8899', 'job' => 'AC Not Cooling', 'status' => LeadStatus::NEW, 'amount' => 12000],

            // --- QUOTED (3) ---
            ['name' => 'Hamza Ali', 'phone' => '+923451234567', 'make' => 'Honda', 'model' => 'City', 'year' => 2017, 'plate' => 'LHR-1010', 'job' => 'Clutch Plate Replacement', 'status' => LeadStatus::QUOTED, 'amount' => 35000],
            ['name' => 'Ayesha Siddiqui', 'phone' => '+923215556677', 'make' => 'Kia', 'model' => 'Picanto', 'year' => 2020, 'plate' => 'KHI-2020', 'job' => 'Suspension Check', 'status' => LeadStatus::QUOTED, 'amount' => 10000],
            ['name' => 'Omer Farooq', 'phone' => '+923338889900', 'make' => 'Toyota', 'model' => 'Fortuner', 'year' => 2022, 'plate' => 'ISB-3030', 'job' => 'Full Body Paint', 'status' => LeadStatus::QUOTED, 'amount' => 85000],

            // --- BOOKED (3) ---
            ['name' => 'Nida Yasir', 'phone' => '+923024567890', 'make' => 'Suzuki', 'model' => 'Cultus', 'year' => 2016, 'plate' => 'KHI-4040', 'job' => 'Engine Tuning', 'status' => LeadStatus::BOOKED, 'amount' => 15000],
            ['name' => 'Tariq Mahmood', 'phone' => '+923147778899', 'make' => 'Honda', 'model' => 'BR-V', 'year' => 2018, 'plate' => 'LHR-5050', 'job' => 'Timing Belt Change', 'status' => LeadStatus::BOOKED, 'amount' => 20000],
            ['name' => 'Sana Khan', 'phone' => '+923351234321', 'make' => 'Hyundai', 'model' => 'Tucson', 'year' => 2021, 'plate' => 'ISB-6060', 'job' => 'Battery Replacement', 'status' => LeadStatus::BOOKED, 'amount' => 18000],

            // --- IN REPAIR (3) ---
            ['name' => 'Sarah Ahmed', 'phone' => '+923219876543', 'make' => 'Toyota', 'model' => 'Corolla', 'year' => 2020, 'plate' => 'ABC-5678', 'job' => 'Synthetic Oil Change', 'status' => LeadStatus::IN_REPAIR, 'amount' => 8500],
            ['name' => 'Usman Ghani', 'phone' => '+923431112233', 'make' => 'Suzuki', 'model' => 'Alto', 'year' => 2015, 'plate' => 'KHI-7070', 'job' => 'Wheel Alignment', 'status' => LeadStatus::IN_REPAIR, 'amount' => 4000],
            ['name' => 'Bilal Butt', 'phone' => '+923209998877', 'make' => 'Honda', 'model' => 'Civic', 'year' => 2019, 'plate' => 'LHR-8080', 'job' => 'Transmission Fluid Flush', 'status' => LeadStatus::IN_REPAIR, 'amount' => 12000],

            // --- COMPLETED (3) ---
            ['name' => 'Bilal Sheikh', 'phone' => '+923335556677', 'make' => 'Suzuki', 'model' => 'Alto', 'year' => 2015, 'plate' => 'XYZ-9012', 'job' => 'Engine Diagnostics', 'status' => LeadStatus::COMPLETED, 'amount' => 5000],
            ['name' => 'Kashif Ali', 'phone' => '+923125554433', 'make' => 'Toyota', 'model' => 'Hilux', 'year' => 2020, 'plate' => 'ISB-9090', 'job' => 'Turbo Replacement', 'status' => LeadStatus::COMPLETED, 'amount' => 120000],
            ['name' => 'Mehwish Hayat', 'phone' => '+923467778811', 'make' => 'Kia', 'model' => 'Sportage', 'year' => 2021, 'plate' => 'KHI-1122', 'job' => 'Brake Fluid Flush', 'status' => LeadStatus::COMPLETED, 'amount' => 6000],
        ];

        foreach ($leadsData as $index => $data) {
            // Contact banate waqt email bhi add kar rahe hain
            $contact = Contact::firstOrCreate(
                ['phone' => $data['phone']],
                [
                    'name' => $data['name'],
                    'email' => strtolower(str_replace(' ', '', $data['name'])) . '@example.com'
                ]
            );

            $followUpDate = null;
            $followUpNote = null;

            if ($index % 4 == 0) {
                $followUpDate = null; // Unscheduled
                $followUpNote = 'Call not scheduled yet.';
            } elseif ($index % 3 == 0) {
                $followUpDate = now()->subDays(2)->format('Y-m-d'); // Overdue
                $followUpNote = 'Customer ko call kiya tha, response nahi aya. Dobara call karein.';
            } elseif ($index % 3 == 1) {
                $followUpDate = now()->format('Y-m-d');
                $followUpNote = 'Aaj shaam 5 baje quote confirm karna hai.';
            } else {
                $followUpDate = now()->addDays(3)->format('Y-m-d');
                $followUpNote = 'Parts ki availability check karni hai.';
            }

            $contact->leads()->create([
                'make' => $data['make'],
                'model' => $data['model'],
                'year' => $data['year'],
                'license_plate' => $data['plate'],
                'service_needed' => $data['job'],
                'status' => $data['status'],
                'total_amount' => $data['amount'], // <-- Yahan amount add ho gaya
                'follow_up_date' => $followUpDate,
                'follow_up_notes' => $followUpNote,
            ]);
        }
    }
}