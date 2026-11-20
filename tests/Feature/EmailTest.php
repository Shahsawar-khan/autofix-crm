<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Lead;
use App\Models\Contact;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    // Main Path
    public function test_can_send_and_log_email()
    {
        $contact = Contact::create(['name' => 'Ali', 'phone' => '03001', 'email' => 'ali@example.com']);
        $lead = $contact->leads()->create([
            'make' => 'Honda', 'model' => 'Civic', 'year' => 2020, 'license_plate' => 'ABC-123', 'service_needed' => 'Oil', 'status' => 'New'
        ]);

        $response = $this->postJson('/api/emails/send', [
            'lead_id' => $lead->id,
            'to_email' => 'ali@example.com',
            'subject' => 'Test Subject',
            'body' => 'Test Body',
        ]);

        $response->assertStatus(201)->assertJsonPath('success', true);
        $this->assertDatabaseHas('email_logs', ['lead_id' => $lead->id, 'subject' => 'Test Subject']);
    }

    // Failure Path (Invalid Email)
    public function test_fails_to_send_email_with_invalid_format()
    {
        $response = $this->postJson('/api/emails/send', [
            'lead_id' => 1,
            'to_email' => 'not-an-email',
            'subject' => 'Test',
            'body' => 'Test',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['to_email']);
    }
}