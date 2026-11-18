<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLeadRequest;
use App\Enums\LeadStatus; // Enum Import kiya
use Illuminate\Validation\Rule; // Rule Import kiya
use App\Http\Requests\UpdateFollowUpRequest;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::with('contact')->latest()->get();
        return response()->json(['success' => true, 'data' => $leads], 200);
    }

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
            'status' => LeadStatus::NEW->value, // Enum use kiya
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully!',
            'data' => $lead->load('contact')
        ], 201);
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        // Cleaner validation using Rule::enum
        $request->validate([
            'status' => ['required', Rule::enum(LeadStatus::class)]
        ]);

        $lead->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $lead
        ], 200);
    }

    public function board()
    {
        $leads = Lead::with('contact')->get();
        
        // Board ab Enum cases utha raha hai (Single source of truth)
        $statuses = LeadStatus::cases(); 
        
        return view('pipeline', compact('leads', 'statuses'));
    }

        public function reminders()
    {
        $leads = Lead::with('contact')->whereNotNull('follow_up_date')->get();
        
        // Leads ko 3 categories mein divide kar rahe hain
        $overdue = $leads->where('follow_up_date', '<', today())->sortBy('follow_up_date');
        $today = $leads->where('follow_up_date', '==', today());
        $upcoming = $leads->where('follow_up_date', '>', today())->sortBy('follow_up_date');

        return view('reminders', compact('overdue', 'today', 'upcoming'));
    }
    public function updateFollowUp(UpdateFollowUpRequest $request, Lead $lead)
    {
        $lead->update([
            'follow_up_date' => $request->follow_up_date,
            'follow_up_notes' => $request->follow_up_notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Follow-up reminder set successfully',
            'data' => $lead
        ], 200);
    }
}