<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use App\Http\Requests\SendEmailRequest;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    // GET: Fetch all templates
    public function getTemplates()
    {
        return response()->json([
            'success' => true,
            'data' => \App\Models\EmailTemplate::all()
        ], 200);
    }

    // GET: Fetch email logs for a specific lead
    public function getLogs($leadId)
    {
        $logs = EmailLog::where('lead_id', $leadId)->latest()->get();
        return response()->json(['success' => true, 'data' => $logs], 200);
    }

    // POST: Send (log) an email
    public function sendEmail(SendEmailRequest $request)
    {
        // Note: Real SMTP integration is skipped for local testing. We just log it.
        $log = EmailLog::create([
            'lead_id' => $request->lead_id,
            'template_id' => $request->template_id,
            'to_email' => $request->to_email,
            'subject' => $request->subject,
            'body' => $request->body,
            'status' => 'sent',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email logged successfully!',
            'data' => $log
        ], 201);
    }

        // UI: Email Logs Page
    public function indexLogs()
    {
        $logs = EmailLog::with('lead.contact')->latest()->paginate(15);
        return view('emails.index', compact('logs'));
    }

    // UI: Templates Page
    public function indexTemplates()
    {
        $templates = \App\Models\EmailTemplate::all();
        return view('templates.index', compact('templates'));
    }
}