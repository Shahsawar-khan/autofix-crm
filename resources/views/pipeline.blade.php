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
            <div class="flex gap-3">
                <a href="/dashboard" class="text-sm font-semibold text-gray-700 hover:text-blue-600 bg-gray-50 border border-gray-200 px-4 py-2 rounded-lg transition">📊 Dashboard</a>
                <a href="/reminders" class="text-sm font-semibold text-orange-600 hover:text-orange-800 bg-orange-50 px-4 py-2 rounded-lg transition">⏰ Reminders</a>
                <a href="/emails" class="text-sm font-semibold text-gray-700 hover:text-blue-600 bg-gray-50 border border-gray-200 px-4 py-2 rounded-lg transition">📧 Email Logs</a>
                <a href="/templates" class="text-sm font-semibold text-gray-700 hover:text-blue-600 bg-gray-50 border border-gray-200 px-4 py-2 rounded-lg transition">📝 Templates</a>
                <a href="/api/leads" target="_blank" class="text-sm font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 px-4 py-2 rounded-lg transition">View API</a>
            </div>
        </div>
    </nav>

    <!-- Board Container -->
    <div class="max-w-[1600px] mx-auto px-4 py-6 flex-1 w-full overflow-y-auto">
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
                @php $statusName = $status->value; @endphp
                
                <div class="bg-white rounded-xl flex flex-col border border-slate-200 border-t-4 {{ $colors[$statusName] ?? 'border-gray-400' }} shadow-sm h-full max-h-[80vh]">
                    
                    <!-- Column Header -->
                    <div class="p-4 flex justify-between items-center bg-slate-50/50 rounded-t-lg border-b border-slate-100">
                        <h2 class="font-bold text-gray-700 uppercase text-xs tracking-wider">{{ $statusName }}</h2>
                        <span class="bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full text-xs font-bold">
                            {{ $leads->where('status', $statusName)->count() }}
                        </span>
                    </div>
                    
                    <!-- Drop Zone -->
                    <div class="flex-1 overflow-y-auto p-3 space-y-3 min-h-[200px]" data-status="{{ $statusName }}">
                        @foreach($leads as $lead)
                            @if($lead->status->value === $statusName)
                                <!-- Card -->
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-slate-200 cursor-grab active:cursor-grabbing hover:shadow-md hover:border-blue-300 transition-all group" data-id="{{ $lead->id }}">
                                    
                                    <!-- Customer Name & Plate -->
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="font-bold text-slate-900 text-base group-hover:text-blue-600 transition">
                                            {{ $lead->contact->name }}
                                        </div>
                                        <div class="text-[10px] font-bold text-slate-700 bg-slate-100 border border-slate-200 px-2 py-1 rounded">
                                            {{ $lead->license_plate }}
                                        </div>
                                    </div>
                                    
                                    <!-- Vehicle Info -->
                                    <div class="text-[13px] text-slate-600 mb-3 flex items-center gap-1.5 font-medium">
                                        🚗 {{ $lead->make }} {{ $lead->model }} ({{ $lead->year }})
                                    </div>
                                    
                                    <!-- Service Needed (Highlighted) -->
                                    <div class="text-xs text-blue-800 bg-blue-50 p-2.5 rounded-md border border-blue-100 font-semibold flex items-center gap-2">
                                        <span>🔧</span>
                                        {{ $lead->service_needed }}
                                    </div>

                                    <!-- Footer: Phone, Email Button & ID -->
                                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                                        <span class="flex items-center gap-1">📞 {{ $lead->contact->phone }}</span>
                                        <div class="flex items-center gap-2">
                                            <!-- Send Email Button -->
                                            <button onclick="openEmailModal({{ $lead->id }}, '{{ addslashes($lead->contact->name) }}', '{{ $lead->contact->email ?? '' }}')" class="text-xs font-bold text-blue-700 bg-blue-100 hover:bg-blue-200 px-2.5 py-1 rounded transition">
                                                📧 Email
                                            </button>
                                            <span class="text-slate-400">#{{ $lead->id }}</span>
                                        </div>
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

    <!-- ========================================== -->
    <!-- EMAIL MODAL (POPUP)                        -->
    <!-- ========================================== -->
    <div id="emailModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 relative">
            <button onclick="closeEmailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
            <h2 class="text-xl font-bold text-gray-800 mb-1">Send Email</h2>
            <p class="text-sm text-gray-500 mb-4" id="emailCustomerName">To: Customer</p>
            
            <form id="emailForm" onsubmit="sendEmail(event)">
                <input type="hidden" id="emailLeadId">
                
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">To Email</label>
                    <input type="email" id="emailTo" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Select Template (Optional)</label>
                    <select id="emailTemplate" onchange="applyTemplate()" class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50">
                        <option value="">-- Custom Email --</option>
                        <!-- Options populated via JS -->
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Subject</label>
                    <input type="text" id="emailSubject" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Message</label>
                    <textarea id="emailBody" rows="5" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeEmailModal()" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-lg transition">Cancel</button>
                    <button type="submit" id="sendBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg transition shadow-sm">
                        Send & Log Email
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SCRIPTS                                    -->
    <!-- ========================================== -->
    <script>
        // ==== 1. DRAG AND DROP LOGIC ====
        const columns = document.querySelectorAll('[data-status]');
        const csrfToken = '{{ csrf_token() }}';
        
        columns.forEach(column => {
            new Sortable(column, {
                group: 'shared',
                animation: 150,
                ghostClass: 'bg-blue-100',
                dragClass: 'rotate-2',
                fallbackOnBody: true,
                swapThreshold: 0.65,
                scroll: true,
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

        // ==== 2. EMAIL MODAL LOGIC ====
        let globalTemplates = [];

        // Fetch templates when page loads
        document.addEventListener('DOMContentLoaded', () => {
            fetch('/api/templates')
                .then(res => res.json())
                .then(data => {
                    globalTemplates = data.data;
                    const select = document.getElementById('emailTemplate');
                    globalTemplates.forEach(t => {
                        select.innerHTML += `<option value="${t.id}">${t.name}</option>`;
                    });
                })
                .catch(err => console.error('Failed to load templates', err));
        });

        function openEmailModal(leadId, customerName, email) {
            document.getElementById('emailLeadId').value = leadId;
            document.getElementById('emailCustomerName').innerText = `To: ${customerName}`;
            document.getElementById('emailTo').value = email;
            document.getElementById('emailSubject').value = '';
            document.getElementById('emailBody').value = '';
            document.getElementById('emailTemplate').value = '';
            
            const modal = document.getElementById('emailModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEmailModal() {
            const modal = document.getElementById('emailModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function applyTemplate() {
            const templateId = document.getElementById('emailTemplate').value;
            if(!templateId) return;
            
            const t = globalTemplates.find(temp => temp.id == templateId);
            if(t) {
                document.getElementById('emailSubject').value = t.subject;
                document.getElementById('emailBody').value = t.body;
            }
        }

        async function sendEmail(e) {
            e.preventDefault();
            const btn = document.getElementById('sendBtn');
            btn.disabled = true;
            btn.innerText = 'Sending...';

            const payload = {
                lead_id: document.getElementById('emailLeadId').value,
                to_email: document.getElementById('emailTo').value,
                subject: document.getElementById('emailSubject').value,
                body: document.getElementById('emailBody').value,
                template_id: document.getElementById('emailTemplate').value || null,
            };

            try {
                const res = await fetch('/api/emails/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                });

                if (res.ok) {
                    closeEmailModal();
                    showToast('✅ Email sent and logged!', 'bg-green-600');
                    // Redirect to logs page after 1.5 seconds
                    setTimeout(() => window.location.href = '/emails', 1500);
                } else {
                    alert('Failed to send email. Please check the inputs.');
                }
            } catch (error) {
                alert('Network error occurred.');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Send & Log Email';
            }
        }

        // ==== 3. TOAST NOTIFICATION ====
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