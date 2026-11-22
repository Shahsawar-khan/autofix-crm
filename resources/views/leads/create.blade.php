<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Lead - AutoFix</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center py-10 font-sans">

    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-white">🚗 Add New Lead</h1>
            <a href="/pipeline" class="text-blue-100 hover:text-white text-sm font-semibold">&larr; Back to Pipeline</a>
        </div>

        <div class="p-6">
            <!-- Validation Errors -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="/leads" method="POST" class="space-y-4">
                @csrf
                
                <!-- Customer Details -->
                <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider border-b pb-2 mb-4">Customer Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Customer Name</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="e.g. 03001234567" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email (Optional)</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="customer@example.com" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <!-- Vehicle Details -->
                <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider border-b pb-2 mt-6 mb-4">Vehicle Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Make (Company)</label>
                        <input type="text" name="make" value="{{ old('make') }}" required placeholder="e.g. Honda" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Model</label>
                        <input type="text" name="model" value="{{ old('model') }}" required placeholder="e.g. Civic" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Year</label>
                        <input type="number" name="year" value="{{ old('year') }}" required placeholder="e.g. 2018" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">License Plate</label>
                        <input type="text" name="license_plate" value="{{ old('license_plate') }}" required placeholder="e.g. LEB-1234" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <!-- Service Details -->
                <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider border-b pb-2 mt-6 mb-4">Service Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Service Needed</label>
                        <input type="text" name="service_needed" value="{{ old('service_needed') }}" required placeholder="e.g. Engine Oil Change" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Estimated Cost (PKR) - Optional</label>
                        <input type="number" name="total_amount" value="{{ old('total_amount', 0) }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition shadow-sm">
                        Save & Add to Pipeline
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>