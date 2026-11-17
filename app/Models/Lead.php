<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'status', // Naya column Task 2 ke liye
    ];

    // Relationship: Ek Lead ek Contact se juda hua hai
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}