<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Contact;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    // ==========================================
    // TASK 1: MAIN PATH (Create Lead)
    // ==========================================
    public function test_can_create_a_valid_lead()
    {
        $response = $this->postJson('/api/leads', [
            'customer_name' => 'Usman Tariq',
            'phone' => '0345-1112223', // Test with dashes to verify normalization
            'make' => 'Kia',
            'model' => 'Sportage',
            'year' => 2022,
            'license_plate' => 'KHI-7788',
            'service_needed' => 'AC Gas Refill'
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('success', true);
                 
        // Verify normalized phone in DB (0345-1112223 -> +923451112223)
        $this->assertDatabaseHas('contacts', ['phone' => '+923451112223']);
    }

    // ==========================================
    // TASK 1: FAILURE PATH (Validation)
    // ==========================================
    public function test_fails_with_clear_errors_when_phone_is_empty_and_name_is_missing()
    {
        // 'customer_name' and 'phone' are sent as empty strings
        $response = $this->postJson('/api/leads', [
            'customer_name' => '', 
            'phone' => '',         
            'make' => 'Honda',     
        ]);

        // Assert 422 and that the keys exist in the errors array (no exact message text).
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['customer_name', 'phone', 'model', 'year', 'license_plate', 'service_needed']);
    }

    // ==========================================
    // TASK 2: MAIN PATH (Update Status for Pipeline Board)
    // ==========================================
    public function test_can_update_lead_status()
    {
        // Create a contact and a lead first
        $contact = Contact::create(['name' => 'Test User', 'phone' => '+923001111111']);
        $lead = $contact->leads()->create([
            'make' => 'Honda', 
            'model' => 'Civic', 
            'year' => 2021, 
            'license_plate' => 'TEST-123', 
            'service_needed' => 'Oil Change', 
            'status' => 'New'
        ]);

        // Change status to 'Booked'
        $response = $this->patchJson("/api/leads/{$lead->id}/status", ['status' => 'Booked']);

        // Assert success and verify DB update
        $response->assertStatus(200)
                 ->assertJsonPath('success', true);
                 
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id, 
            'status' => 'Booked'
        ]);
    }

    // ==========================================
    // TASK 2: FAILURE PATH (Invalid Status)
    // ==========================================
    public function test_fails_to_update_with_invalid_status()
    {
        $contact = Contact::create(['name' => 'Test User 2', 'phone' => '+923002222222']);
        $lead = $contact->leads()->create([
            'make' => 'Toyota', 
            'model' => 'Corolla', 
            'year' => 2019, 
            'license_plate' => 'TEST-456', 
            'service_needed' => 'Tuning', 
            'status' => 'New'
        ]);

        // Try to update with a status that doesn't exist in our enum
        $response = $this->patchJson("/api/leads/{$lead->id}/status", ['status' => 'Hacked']);

        // Assert 422 and specific validation error for 'status'
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['status']);
    }

        // ==========================================
    // TASK 3: MAIN PATH (Set Follow-up Reminder)
    // ==========================================
    public function test_can_set_follow_up_reminder()
    {
        $contact = \App\Models\Contact::create(['name' => 'Test User', 'phone' => '+923009998877']);
        $lead = $contact->leads()->create([
            'make' => 'Honda', 'model' => 'Civic', 'year' => 2021, 'license_plate' => 'TST-999', 'service_needed' => 'Oil Change', 'status' => 'New'
        ]);

        // Future date bhej rahe hain
        $futureDate = now()->addDays(2)->format('Y-m-d');

        $response = $this->patchJson("/api/leads/{$lead->id}/follow-up", [
            'follow_up_date' => $futureDate,
            'follow_up_notes' => 'Customer ko quote confirm karwana hai'
        ]);

        $response->assertStatus(200)->assertJsonPath('success', true);
        $this->assertDatabaseHas('leads', ['id' => $lead->id, 'follow_up_date' => $futureDate . ' 00:00:00']);
    }

    // ==========================================
    // TASK 3: FAILURE PATH (Past Date Validation)
    // ==========================================
    public function test_fails_to_set_past_follow_up_date()
    {
        $contact = \App\Models\Contact::create(['name' => 'Test User 2', 'phone' => '+923008887766']);
        $lead = $contact->leads()->create([
            'make' => 'Toyota', 'model' => 'Yaris', 'year' => 2020, 'license_plate' => 'TST-888', 'service_needed' => 'AC Repair', 'status' => 'New'
        ]);

        // Past date bhej rahe hain (jo fail honi chahiye)
        $pastDate = now()->subDays(1)->format('Y-m-d');

        $response = $this->patchJson("/api/leads/{$lead->id}/follow-up", [
            'follow_up_date' => $pastDate,
        ]);

        // 422 error aana chahiye kyunke past date allow nahi hai
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['follow_up_date']);
    }

        // ==========================================
    // TASK 3: DASHBOARD BUCKETS & MARK DONE TESTS
    // ==========================================
    
    public function test_reminders_dashboard_shows_overdue_lead()
    {
        $contact = \App\Models\Contact::create(['name' => 'Overdue Test', 'phone' => '+923001112233']);
        
        // 1. Set follow-up date to yesterday (Overdue)
        $lead = $contact->leads()->create([
            'make' => 'Honda', 'model' => 'Civic', 'year' => 2020, 'license_plate' => 'OVD-001', 'service_needed' => 'Oil Change', 'status' => 'New',
            'follow_up_date' => now()->subDay()->format('Y-m-d'),
            'follow_up_completed' => false
        ]);

        // 2. Hit the dashboard
        $response = $this->get('/reminders');
        
        // 3. Assert it appears in the dashboard
        $response->assertStatus(200);
        $response->assertSee('Overdue Test');
        $response->assertSee('OVD-001');
    }

    public function test_marking_follow_up_done_removes_lead_from_dashboard()
    {
        $contact = \App\Models\Contact::create(['name' => 'Done Test', 'phone' => '+923001112244']);
        $lead = $contact->leads()->create([
            'make' => 'Toyota', 'model' => 'Yaris', 'year' => 2021, 'license_plate' => 'DON-002', 'service_needed' => 'Tuning', 'status' => 'New',
            'follow_up_date' => now()->format('Y-m-d'), // Today
            'follow_up_completed' => false
        ]);

        // 1. Assert it is visible first
        $this->get('/reminders')->assertSee('Done Test');

        // 2. Hit the Mark Done API
        $this->patchJson("/api/leads/{$lead->id}/follow-up/complete")->assertStatus(200);

        // 3. Hit dashboard again, assert it's gone
        $this->get('/reminders')->assertDontSee('Done Test');
    }

    public function test_marking_invalid_lead_id_returns_404_not_500()
    {
        // IDOR Protection: Invalid ID should return 404 (thanks to Route Model Binding)
        $this->patchJson("/api/leads/999999/follow-up/complete")->assertStatus(404);
    }
}