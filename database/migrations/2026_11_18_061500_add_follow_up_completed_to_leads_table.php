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
            // Default false hoga (matlab call nahi hui)
            $table->boolean('follow_up_completed')->default(false)->after('follow_up_notes');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('follow_up_completed');
        });
    }
};
