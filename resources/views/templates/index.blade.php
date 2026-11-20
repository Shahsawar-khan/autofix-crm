<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Templates - AutoFix</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">📝 Email Templates</h1>
            <div class="space-x-4">
                <a href="/emails" class="text-blue-600 hover:underline">View Logs</a>
                <a href="/pipeline" class="text-blue-600 hover:underline">Back to Pipeline</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($templates as $template)
                <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                    <h2 class="font-bold text-lg text-blue-700 mb-2">{{ $template->name }}</h2>
                    <div class="text-sm font-semibold text-gray-700 mb-2">Subject: {{ $template->subject }}</div>
                    <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded border border-gray-100 italic">
                        "{{ $template->body }}"
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>