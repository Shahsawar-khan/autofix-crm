<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getMetrics()
    {
        // 1. Total Revenue (Sirf Completed jobs ka paisa count karenge)
        $totalRevenue = Lead::where('status', 'Completed')->sum('total_amount');

        // 2. Pipeline Value (Active jobs ki total value)
        $pipelineValue = Lead::whereNotIn('status', ['Completed'])->sum('total_amount');

        // 3. Jobs count by Status (SQL Group By use karke)
        $jobsByStatus = Lead::select('status', DB::raw('count(*) as count'))
                            ->groupBy('status')
                            ->pluck('count', 'status');

        // 4. Pending Reminders
        $pendingReminders = Lead::whereNotNull('follow_up_date')
                                ->where('follow_up_completed', false)
                                ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_revenue' => $totalRevenue,
                'pipeline_value' => $pipelineValue,
                'total_leads' => Lead::count(),
                'pending_reminders' => $pendingReminders,
                'jobs_by_status' => $jobsByStatus,
            ]
        ], 200);
    }
        public function index()
    {
        $totalRevenue = Lead::where('status', 'Completed')->sum('total_amount');
        $pipelineValue = Lead::whereNotIn('status', ['Completed'])->sum('total_amount');
        $pendingReminders = Lead::whereNotNull('follow_up_date')->where('follow_up_completed', false)->count();
        
        // Chart ke liye data fetch kar rahe hain
        $jobsByStatus = Lead::select('status', DB::raw('count(*) as count'))
                            ->groupBy('status')
                            ->pluck('count', 'status');

        // Recent emails logs for the side panel
        $recentEmails = \App\Models\EmailLog::with('lead.contact')->latest()->take(5)->get();

        return view('dashboard', compact('totalRevenue', 'pipelineValue', 'pendingReminders', 'jobsByStatus', 'recentEmails'));
    }
}