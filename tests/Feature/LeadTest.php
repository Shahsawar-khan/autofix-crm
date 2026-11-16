<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

        // Success Test
        public function test_can_create_a_valid_lead()
    {
        $response = $this->postJson('/api/leads', [
            'customer_name' => 'Usman Tariq',
            'phone' => '0345-1112223', // Test with dashes
            'make' => 'Kia',
            'model' => 'Sportage',
            'year' => 2022,
            'license_plate' => 'KHI-7788',
            'service_needed' => 'AC Gas Refill'
        ]);

        $response->assertStatus(201)->assertJsonPath('success', true);
        // Verify normalized phone in DB
        $this->assertDatabaseHas('contacts', ['phone' => '+923451112223']);
    }

    // Failure Test (Ab 4 errors aayenge)
    public function test_fails_if_required_fields_are_missing()
    {
        $response = $this->postJson('/api/leads', [
            'customer_name' => 'Ali'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['phone', 'make', 'model', 'year', 'license_plate', 'service_needed']);
    }
}