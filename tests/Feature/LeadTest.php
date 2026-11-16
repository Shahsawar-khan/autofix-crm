<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_a_valid_lead()
    {
        $response = $this->postJson('/api/leads', [
            'customer_name' => 'Usman Tariq',
            'phone' => '03001234567',
            'vehicle_info' => 'Kia Sportage 2022',
            'service_needed' => 'AC Gas Refill'
        ]);

        $response->assertStatus(201)->assertJsonPath('success', true);
    }

        public function test_fails_if_required_fields_are_missing()
    {
        $response = $this->postJson('/api/leads', [
            'customer_name' => 'Ali'
        ]);

        $response->assertStatus(422) // Standard Laravel validation code
                 ->assertJsonValidationErrors(['phone', 'vehicle_info', 'service_needed']); // Native assertion
    }
}