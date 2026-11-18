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
            // Index lagana zaroori hai taake whereDate query fast ho
            $table->index('follow_up_date'); 
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['follow_up_date']);
        });
    }
};
