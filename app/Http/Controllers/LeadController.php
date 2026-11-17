<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLeadRequest;

class LeadController extends Controller
{
    // Get all leads
    public function index()
    {
        $leads = Lead::with('contact')->latest()->get();
        return response()->json(['success' => true, 'data' => $leads], 200);
    }

    // Create a new lead
    public function store(StoreLeadRequest $request) 
    {
        $contact = Contact::firstOrCreate(
            ['phone' => $request->phone],
            ['name' => $request->customer_name]
        );

        $lead = $contact->leads()->create([
            'make' => $request->make,
            'model' => $request->model,
            'year' => $request->year,
            'license_plate' => $request->license_plate,
            'service_needed' => $request->service_needed,
            'status' => 'New', // Default status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully!',
            'data' => $lead->load('contact')
        ], 201);
    }

    // Update lead status (Yeh function missing tha)
    public function updateStatus(Request $request, Lead $lead)
    {
        // Validation: Sirf inhi statuses ko allow karenge
        $request->validate([
            'status' => 'required|string|in:New,Quoted,Booked,In Repair,Completed'
        ]);

        $lead->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $lead
        ], 200);
    }

        // Pipeline Board View
    public function board()
    {
        $leads = Lead::with('contact')->get();
        $statuses = ['New', 'Quoted', 'Booked', 'In Repair', 'Completed'];
        return view('pipeline', compact('leads', 'statuses'));
    }
}