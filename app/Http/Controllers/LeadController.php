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
        // Agar validation fail hui, toh Laravel khud 422 return kar dega.
        // Humare paas yahan aayega sirf valid data.
        $lead = Lead::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully!',
            'data' => $lead
        ], 201);
    }
}