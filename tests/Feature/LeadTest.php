<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    // ==========================================
    // 1. MAIN PATH (Success Test)
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
    // 2. FAILURE PATH (Mentor ki Demand)
    // ==========================================
        // ==========================================
    // 2. FAILURE PATH (Mentor's refined advice)
    // ==========================================
    public function test_fails_with_clear_errors_when_phone_is_empty_and_name_is_missing()
    {
        // 'customer_name' and 'phone' are sent as empty strings
        $response = $this->postJson('/api/leads', [
            'customer_name' => '', 
            'phone' => '',         
            'make' => 'Honda',     
        ]);

        // Just assert the 422 status and that the keys exist in the errors array.
        // We no longer pin the exact message text.
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['customer_name', 'phone', 'model', 'year', 'license_plate', 'service_needed']);
    }
}