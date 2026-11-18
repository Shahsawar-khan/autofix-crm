<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; // <-- Ye line add karein
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase; // <-- Ye line add karein

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/api/leads');
        $response->assertStatus(200);
    }
}