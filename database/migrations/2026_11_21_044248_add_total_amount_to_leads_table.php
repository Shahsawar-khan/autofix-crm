<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Paison ke liye decimal column (e.g., 15000.00)
            $table->decimal('total_amount', 10, 2)->default(0)->after('service_needed');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('total_amount');
        });
    }
};
