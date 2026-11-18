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
        // 1. Overdue (Date is less than today)
        $overdue = Lead::with('contact')
            ->whereNotNull('follow_up_date')
             ->where('follow_up_completed', false)
            ->whereDate('follow_up_date', '<', now()->toDateString())
            ->orderBy('follow_up_date', 'asc')
            ->get();

        // 2. Today (Date is exactly today)
        $today = Lead::with('contact')
             ->where('follow_up_completed', false)
            ->whereDate('follow_up_date', now()->toDateString())
            ->orderBy('follow_up_date', 'asc')
            ->get();

        // 3. Upcoming (Date is greater than today)
        $upcoming = Lead::with('contact')
             ->where('follow_up_completed', false)
            ->whereDate('follow_up_date', '>', now()->toDateString())
            ->orderBy('follow_up_date', 'asc')
            ->get();

        // 4. Unscheduled (No date set - Mentor ki demand)
        $unscheduled = Lead::with('contact')
            ->whereNull('follow_up_date')
             ->where('follow_up_completed', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reminders', compact('overdue', 'today', 'upcoming', 'unscheduled'));
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
           public function completeFollowUp(Lead $lead)
    {
        // Direct save instead of update() to avoid fillable issues
        $lead->follow_up_completed = true; 
        $lead->save(); 
        
        return response()->json([
            'success' => true,
            'message' => 'Follow-up marked as completed'
        ], 200);
    }
}