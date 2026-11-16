<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Http\Requests\StoreLeadRequest; // Import FormRequest

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::latest()->get();
        return response()->json(['success' => true, 'data' => $leads], 200);
    }

    // Yahan StoreLeadRequest inject ho gaya
        public function store(StoreLeadRequest $request) 
    {
        // Check if contact exists by phone, otherwise create it
        $contact = \App\Models\Contact::firstOrCreate(
            ['phone' => $request->phone],
            ['name' => $request->customer_name]
        );

        // Create lead attached to this contact
        $lead = $contact->leads()->create([
            'make' => $request->make,
            'model' => $request->model,
            'year' => $request->year,
            'license_plate' => $request->license_plate,
            'service_needed' => $request->service_needed,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully!',
            'data' => $lead->load('contact') // Load contact details
        ], 201);
    }
}