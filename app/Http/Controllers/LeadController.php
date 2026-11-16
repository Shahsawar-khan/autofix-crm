<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::latest()->get();
        return response()->json(['success' => true, 'data' => $leads], 200);
    }

    public function store(Request $request)
    {
                $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            // Shape & Type check: Only digits, spaces, +, and - allowed. Max 20 chars.
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9\+\-\s]+$/'], 
            'vehicle_info' => 'required|string|max:255',
            'service_needed' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error: Please check your input format.',
                'errors' => $validator->errors()
            ], 400); 
        }

        $lead = Lead::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully!',
            'data' => $lead
        ], 201);
    }
}