<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
           public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            // Link to Contact
            $table->foreignId('contact_id')->constrained()->onDelete('cascade'); 
            
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->string('license_plate');
            $table->string('service_needed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};