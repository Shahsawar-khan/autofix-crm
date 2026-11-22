<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Logs - AutoFix</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-800">📧 Email Logs</h1>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="/dashboard" class="text-blue-600 hover:underline text-sm font-semibold">Dashboard</a>
                <a href="/templates" class="text-blue-600 hover:underline text-sm font-semibold">Templates</a>
                <a href="/pipeline" class="text-blue-600 hover:underline text-sm font-semibold">Back to Pipeline</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Table Scrollable on Mobile -->
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-bold">
                        <tr>
                            <th class="p-4">To (Customer)</th>
                            <th class="p-4">Subject</th>
                            <th class="p-4">Vehicle</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Sent At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="p-4">
                                    <div class="font-bold text-gray-800">{{ $log->lead->contact->name ?? 'Unknown' }}</div>
                                    <div class="text-sm text-gray-500">{{ $log->to_email }}</div>
                                </td>
                                <td class="p-4 text-sm text-gray-700">{{ $log->subject }}</td>
                                <td class="p-4 text-sm text-gray-700">
                                    {{ $log->lead->make ?? '' }} {{ $log->lead->model ?? '' }} ({{ $log->lead->license_plate ?? '' }})
                                </td>
                                <td class="p-4">
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">✓ {{ ucfirst($log->status) }}</span>
                                </td>
                                <td class="p-4 text-sm text-gray-500">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-500">No emails sent yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $logs->links() }}</div>
    </div>
</body>
</html>