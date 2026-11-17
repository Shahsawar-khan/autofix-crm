<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoFix Workshop - Pipeline</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <style>
        body { overflow: hidden; } /* Ye neeche wali scroll line ko khatam kar dega */
        /* Custom Scrollbar for a cleaner look */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased h-screen flex flex-col">

    <!-- Header -->
    <nav class="bg-white shadow-sm border-b border-gray-200 flex-shrink-0">
        <div class="max-w-[1600px] mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="text-blue-600">AutoFix</span> Workshop Pipeline
            </h1>
            <a href="/api/leads" target="_blank" class="text-sm font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 px-4 py-2 rounded-lg transition">View API JSON</a>
        </div>
    </nav>

    <!-- Board Container (CSS Grid Use kiya hai taake horizontal scroll khatam ho) -->
    <div class="max-w-[1600px] mx-auto px-4 py-6 flex-1 w-full overflow-y-auto">
        <!-- Grid: Screen par 5 columns barabar fit honge -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 h-full items-start">
            
            @php
                $colors = [
                    'New' => 'border-blue-500',
                    'Quoted' => 'border-yellow-500',
                    'Booked' => 'border-purple-500',
                    'In Repair' => 'border-orange-500',
                    'Completed' => 'border-green-500'
                ];
            @endphp

            @foreach($statuses as $status)
                <div class="bg-white rounded-xl flex flex-col border border-slate-200 border-t-4 {{ $colors[$status] ?? 'border-gray-400' }} shadow-sm h-full max-h-[80vh]">
                    
                    <!-- Column Header -->
                    <div class="p-4 flex justify-between items-center bg-slate-50/50 rounded-t-lg border-b border-slate-100">
                        <h2 class="font-bold text-gray-700 uppercase text-xs tracking-wider">{{ $status }}</h2>
                        <span class="bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full text-xs font-bold">
                            {{ $leads->where('status', $status)->count() }}
                        </span>
                    </div>
                    
                    <!-- Drop Zone -->
                    <div class="flex-1 overflow-y-auto p-3 space-y-3 min-h-[200px]" data-status="{{ $status }}">
                        @foreach($leads as $lead)
                            @if($lead->status === $status)
                                <!-- Card -->
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-slate-200 cursor-grab active:cursor-grabbing hover:shadow-md transition-all group" data-id="{{ $lead->id }}">
                                    
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition">{{ $lead->contact->name }}</div>
                                        <div class="text-[10px] font-bold text-slate-700 bg-slate-100 border border-slate-200 px-2 py-1 rounded">{{ $lead->license_plate }}</div>
                                    </div>
                                    
                                    <div class="text-xs text-slate-500 mb-3 flex items-center gap-1">
                                        🚗 {{ $lead->make }} {{ $lead->model }} ({{ $lead->year }})
                                    </div>
                                    
                                    <div class="text-xs text-slate-700 bg-slate-50 p-2.5 rounded border border-slate-100 font-medium">
                                        🔧 {{ $lead->service_needed }}
                                    </div>

                                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                                        <span>📞 {{ $lead->contact->phone }}</span>
                                        <span>#{{ $lead->id }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-5 right-5 px-6 py-3 rounded-lg shadow-xl text-white font-bold transition-all duration-300 opacity-0 translate-y-2 pointer-events-none z-50">Message</div>

    <!-- Drag & Drop Logic -->
    <script>
        const columns = document.querySelectorAll('[data-status]');
        const csrfToken = '{{ csrf_token() }}';
        
        columns.forEach(column => {
            new Sortable(column, {
                group: 'shared',
                animation: 150,
                ghostClass: 'bg-blue-100',
                dragClass: 'rotate-2',
                onEnd: async function (evt) {
                    const leadId = evt.item.getAttribute('data-id');
                    const newStatus = evt.to.getAttribute('data-status');
                    const oldStatus = evt.from.getAttribute('data-status');

                    if (newStatus === oldStatus) return;

                    try {
                        const response = await fetch(`/api/leads/${leadId}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ status: newStatus })
                        });

                        if (!response.ok) throw new Error('Validation Error');

                        showToast('✅ Status updated to ' + newStatus, 'bg-green-600');
                        
                        setTimeout(() => window.location.reload(), 800);

                    } catch (error) {
                        evt.from.appendChild(evt.item);
                        showToast('❌ Error updating status. Reverted.', 'bg-red-600');
                    }
                }
            });
        });

        function showToast(message, colorClass) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = `fixed bottom-5 right-5 px-6 py-3 rounded-lg shadow-xl text-white font-bold transition-all duration-300 opacity-100 translate-y-0 z-50 ${colorClass}`;
            
            setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', 'translate-y-2');
            }, 3000);
        }
    </script>
</body>
</html>