<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoFix Workshop - Follow-up Reminders</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { background-color: #f8fafc; font-family: 'Inter', sans-serif; } </style>
</head>
<body class="h-screen flex flex-col">

    <!-- Header -->
    <nav class="bg-white shadow-sm border-b border-gray-200 flex-shrink-0">
        <div class="max-w-[1600px] mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="text-blue-600">AutoFix</span> Follow-up Reminders
            </h1>
            <div class="flex gap-4">
                <a href="/pipeline" class="text-sm font-semibold text-gray-600 hover:text-blue-600 bg-gray-50 px-4 py-2 rounded-lg transition border border-gray-200">📋 View Pipeline</a>
                <a href="/dashboard" class="text-sm font-semibold text-gray-700 hover:text-blue-600 bg-gray-50 border border-gray-200 px-4 py-2 rounded-lg transition">📊 Dashboard</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-[1600px] mx-auto px-6 py-8 w-full flex-1 overflow-y-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- 1. Overdue Column (Red) -->
            <div class="bg-white rounded-xl shadow-sm border-t-4 border-red-500 flex flex-col">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-red-50/50 rounded-t-lg">
                    <h2 class="font-bold text-red-700 uppercase text-sm tracking-wider flex items-center gap-2">🚨 Overdue</h2>
                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">{{ $overdue->count() }}</span>
                </div>
                <div class="p-3 space-y-3">
                    @forelse($overdue as $lead)
                        <div class="bg-white border border-red-100 p-4 rounded-lg shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <div class="font-bold text-slate-800">{{ $lead->contact->name }}</div>
                                <div class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded">{{ $lead->follow_up_date->format('M d, Y') }}</div>
                            </div>
                            <div class="text-xs text-slate-600 mb-2">🚗 {{ $lead->make }} {{ $lead->model }} ({{ $lead->license_plate }})</div>
                            <div class="text-xs bg-slate-50 p-2 rounded text-slate-700 border border-slate-100">
                                📝 {{ $lead->follow_up_notes ?? 'No notes provided' }}
                            </div>
                            <div class="mt-4 flex justify-between items-center border-t border-slate-100 pt-3">
                                <div class="text-[11px] text-slate-500 font-medium">📞 {{ $lead->contact->phone }}</div>
                                <button onclick="markDone({{ $lead->id }})" class="text-xs font-bold text-white bg-green-500 hover:bg-green-600 px-3 py-1.5 rounded transition shadow-sm">
                                    ✓ Mark Done
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-400 py-10 text-sm">No overdue reminders. Good job!</div>
                    @endforelse
                </div>
            </div>

            <!-- 2. Today Column (Yellow) -->
            <div class="bg-white rounded-xl shadow-sm border-t-4 border-yellow-500 flex flex-col">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-yellow-50/50 rounded-t-lg">
                    <h2 class="font-bold text-yellow-700 uppercase text-sm tracking-wider flex items-center gap-2">📅 Today</h2>
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">{{ $today->count() }}</span>
                </div>
                <div class="p-3 space-y-3">
                    @forelse($today as $lead)
                        <div class="bg-white border border-yellow-100 p-4 rounded-lg shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <div class="font-bold text-slate-800">{{ $lead->contact->name }}</div>
                                <div class="text-[10px] font-bold text-yellow-600 bg-yellow-50 px-2 py-1 rounded">Today</div>
                            </div>
                            <div class="text-xs text-slate-600 mb-2">🚗 {{ $lead->make }} {{ $lead->model }} ({{ $lead->license_plate }})</div>
                            <div class="text-xs bg-slate-50 p-2 rounded text-slate-700 border border-slate-100">
                                📝 {{ $lead->follow_up_notes ?? 'No notes provided' }}
                            </div>
                            <div class="mt-4 flex justify-between items-center border-t border-slate-100 pt-3">
                                <div class="text-[11px] text-slate-500 font-medium">📞 {{ $lead->contact->phone }}</div>
                                <button onclick="markDone({{ $lead->id }})" class="text-xs font-bold text-white bg-green-500 hover:bg-green-600 px-3 py-1.5 rounded transition shadow-sm">
                                    ✓ Mark Done
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-400 py-10 text-sm">No reminders for today.</div>
                    @endforelse
                </div>
            </div>

            <!-- 3. Upcoming Column (Green) -->
            <div class="bg-white rounded-xl shadow-sm border-t-4 border-green-500 flex flex-col">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-green-50/50 rounded-t-lg">
                    <h2 class="font-bold text-green-700 uppercase text-sm tracking-wider flex items-center gap-2">⏳ Upcoming</h2>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">{{ $upcoming->count() }}</span>
                </div>
                <div class="p-3 space-y-3">
                    @forelse($upcoming as $lead)
                        <div class="bg-white border border-green-100 p-4 rounded-lg shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <div class="font-bold text-slate-800">{{ $lead->contact->name }}</div>
                                <div class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-1 rounded">{{ $lead->follow_up_date->format('M d, Y') }}</div>
                            </div>
                            <div class="text-xs text-slate-600 mb-2">🚗 {{ $lead->make }} {{ $lead->model }} ({{ $lead->license_plate }})</div>
                            <div class="text-xs bg-slate-50 p-2 rounded text-slate-700 border border-slate-100">
                                📝 {{ $lead->follow_up_notes ?? 'No notes provided' }}
                            </div>
                            <div class="mt-4 flex justify-between items-center border-t border-slate-100 pt-3">
                                <div class="text-[11px] text-slate-500 font-medium">📞 {{ $lead->contact->phone }}</div>
                                <button onclick="markDone({{ $lead->id }})" class="text-xs font-bold text-white bg-green-500 hover:bg-green-600 px-3 py-1.5 rounded transition shadow-sm">
                                    ✓ Mark Done
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-400 py-10 text-sm">No upcoming reminders.</div>
                    @endforelse
                </div>
            </div>

            <!-- 4. Unscheduled Column (Gray) -->
            <div class="bg-white rounded-xl shadow-sm border-t-4 border-gray-500 flex flex-col">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-lg">
                    <h2 class="font-bold text-gray-700 uppercase text-sm tracking-wider flex items-center gap-2">⚠️ Unscheduled</h2>
                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-bold">{{ $unscheduled->count() }}</span>
                </div>
                <div class="p-3 space-y-3">
                    @forelse($unscheduled as $lead)
                        <div class="bg-white border border-gray-200 p-4 rounded-lg shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <div class="font-bold text-slate-800">{{ $lead->contact->name }}</div>
                                <div class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded">No Date</div>
                            </div>
                            <div class="text-xs text-slate-600 mb-2">🚗 {{ $lead->make }} {{ $lead->model }} ({{ $lead->license_plate }})</div>
                            <div class="text-xs bg-slate-50 p-2 rounded text-slate-700 border border-slate-100">
                                📝 {{ $lead->service_needed }}
                            </div>
                            <div class="mt-4 flex justify-between items-center border-t border-slate-100 pt-3">
                                <div class="text-[11px] text-slate-500 font-medium">📞 {{ $lead->contact->phone }}</div>
                                <button onclick="markDone({{ $lead->id }})" class="text-xs font-bold text-white bg-green-500 hover:bg-green-600 px-3 py-1.5 rounded transition shadow-sm">
                                    ✓ Mark Done
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-400 py-10 text-sm">All leads have dates set.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function markDone(leadId) {
            console.log('Button clicked for Lead ID:', leadId);
            
            fetch(`/api/leads/${leadId}/follow-up/complete`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if(response.ok) {
                    window.location.reload(); 
                } else {
                    alert('API Error: ' + response.status);
                }
            })
            .catch(error => {
                alert('Network Error: ' + error);
            });
        }
    </script>
</body>
</html>