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
    ];

    // Enum Casting (Single source of truth)
    protected $casts = [
        'status' => LeadStatus::class,
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}