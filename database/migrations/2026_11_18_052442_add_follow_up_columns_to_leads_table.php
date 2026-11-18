<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Follow up date nullable hogi (har gaari ko call nahi karni hoti)
            $table->date('follow_up_date')->nullable()->after('status');
            // Notes (jaise: "Customer ne kaha 3 baje call karna")
            $table->string('follow_up_notes')->nullable()->after('follow_up_date');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['follow_up_date', 'follow_up_notes']);
        });
    }
};