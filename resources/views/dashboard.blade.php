<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoFix Workshop - Sales Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-50 font-sans antialiased h-screen flex flex-col">

    <!-- Header -->
    <nav class="bg-white shadow-sm border-b border-gray-200 flex-shrink-0">
        <div class="max-w-[1600px] mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="text-blue-600">AutoFix</span> Sales Dashboard
            </h1>
            <div class="flex gap-2">
                <a href="/dashboard" class="text-sm font-semibold text-blue-600 bg-blue-50 px-3 py-2 rounded-lg border border-blue-100">📊 Dashboard</a>
                <a href="/pipeline" class="text-sm font-semibold text-gray-600 hover:text-blue-600 bg-gray-50 border border-gray-200 px-3 py-2 rounded-lg transition">📋 Pipeline</a>
                <a href="/reminders" class="text-sm font-semibold text-gray-600 hover:text-orange-600 bg-gray-50 border border-gray-200 px-3 py-2 rounded-lg transition">⏰ Reminders</a>
                <a href="/emails" class="text-sm font-semibold text-gray-600 hover:text-blue-600 bg-gray-50 border border-gray-200 px-3 py-2 rounded-lg transition">📧 Logs</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-[1600px] mx-auto px-6 py-8 w-full flex-1 overflow-y-auto">
        
        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Revenue -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-6 shadow-sm text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-green-100 text-sm font-medium uppercase tracking-wider mb-1">Total Revenue (Completed)</p>
                        <h3 class="text-3xl font-bold">PKR {{ number_format($totalRevenue) }}</h3>
                    </div>
                    <div class="text-4xl opacity-50">💰</div>
                </div>
            </div>

            <!-- Pipeline Value -->
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-6 shadow-sm text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-blue-100 text-sm font-medium uppercase tracking-wider mb-1">Active Pipeline Value</p>
                        <h3 class="text-3xl font-bold">PKR {{ number_format($pipelineValue) }}</h3>
                    </div>
                    <div class="text-4xl opacity-50">📈</div>
                </div>
            </div>

            <!-- Pending Reminders -->
            <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-xl p-6 shadow-sm text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-orange-100 text-sm font-medium uppercase tracking-wider mb-1">Pending Reminders</p>
                        <h3 class="text-3xl font-bold">{{ $pendingReminders }}</h3>
                    </div>
                    <div class="text-4xl opacity-50">⏰</div>
                </div>
            </div>

            <!-- Total Jobs -->
            <div class="bg-gradient-to-br from-purple-500 to-fuchsia-600 rounded-xl p-6 shadow-sm text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-purple-100 text-sm font-medium uppercase tracking-wider mb-1">Total Jobs (Leads)</p>
                        <h3 class="text-3xl font-bold">{{ $jobsByStatus->sum() }}</h3>
                    </div>
                    <div class="text-4xl opacity-50">🚗</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart Column -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-1">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Jobs by Pipeline Stage</h2>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Recent Emails / Activity Column -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 lg:col-span-2 flex flex-col">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-800">Recent Email Activity</h2>
                    <a href="/emails" class="text-sm font-semibold text-blue-600 hover:underline">View All</a>
                </div>
                <div class="flex-1 p-0 overflow-y-auto">
                    <table class="w-full text-left border-collapse">
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentEmails as $log)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 w-12 text-center text-xl">📧</td>
                                    <td class="p-4">
                                        <div class="font-bold text-gray-800">{{ $log->lead->contact->name ?? 'Unknown' }}</div>
                                        <div class="text-sm text-gray-500">{{ $log->subject }}</div>
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</div>
                                        <div class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded mt-1 inline-block">Sent</div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="p-6 text-center text-gray-500">No emails sent yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Chart.js Script -->
    <script>
        // Data pass from PHP to JS
        const statusLabels = @json($jobsByStatus->keys());
        const statusData = @json($jobsByStatus->values());

        const ctx = document.getElementById('statusChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut', // Ya 'bar' bhi use kar sakte hain
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: [
                        '#3b82f6', // New (Blue)
                        '#eab308', // Quoted (Yellow)
                        '#a855f7', // Booked (Purple)
                        '#f97316', // In Repair (Orange)
                        '#22c55e'  // Completed (Green)
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: { family: "'Inter', sans-serif", size: 12 }
                        }
                    }
                },
                cutout: '70%' // Doughnut hole size
            }
        });
    </script>
</body>
</html>