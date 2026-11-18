<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\LeadStatus; // Enum Import kiya

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'make',
        'model',
        'year',
        'license_plate',
        'service_needed',
        'status',
        'follow_up_date',   // <-- Naya
        'follow_up_notes',  // <-- Naya
        'follow_up_completed',
    ];

    // Enum Casting (Single source of truth)
    protected $casts = [
        'status' => LeadStatus::class,
        'follow_up_date' => 'date', // <-- Date cast kiya taake Carbon object milay
        'follow_up_completed' => 'boolean',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}