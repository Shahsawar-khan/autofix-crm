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
}