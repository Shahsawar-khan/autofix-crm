<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Contact;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    // Main Path
    public function test_can_fetch_dashboard_metrics()
    {
        $contact = Contact::create(['name' => 'Test', 'phone' => '0300', 'email' => 'test@test.com']);
        
        // Create 2 Completed leads (Revenue = 10000 + 15000 = 25000)
        $contact->leads()->create(['make' => 'H', 'model' => 'C', 'year' => 2020, 'license_plate' => 'A', 'service_needed' => 'X', 'status' => 'Completed', 'total_amount' => 10000]);
        $contact->leads()->create(['make' => 'H', 'model' => 'C', 'year' => 2020, 'license_plate' => 'B', 'service_needed' => 'X', 'status' => 'Completed', 'total_amount' => 15000]);
        
        // Create 1 Active lead (Pipeline Value = 5000)
        $contact->leads()->create(['make' => 'T', 'model' => 'Y', 'year' => 2020, 'license_plate' => 'C', 'service_needed' => 'Y', 'status' => 'In Repair', 'total_amount' => 5000, 'follow_up_date' => now(), 'follow_up_completed' => false]);

        $response = $this->getJson('/api/dashboard/metrics');

        $response->assertStatus(200)
                 ->assertJsonPath('data.total_revenue', 25000)
                 ->assertJsonPath('data.pipeline_value', 5000)
                 ->assertJsonPath('data.total_leads', 3)
                 ->assertJsonPath('data.pending_reminders', 1);
    }

    // Failure Path (Empty Database Handling)
    public function test_dashboard_returns_zeros_when_no_data()
    {
        // Database khali hai, crash nahi hona chahiye
        $response = $this->getJson('/api/dashboard/metrics');

        $response->assertStatus(200)
                 ->assertJsonPath('data.total_revenue', 0)
                 ->assertJsonPath('data.total_leads', 0);
    }
}