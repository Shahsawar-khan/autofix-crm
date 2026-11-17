<?php

namespace App\Enums;

enum LeadStatus: string
{
    case NEW = 'New';
    case QUOTED = 'Quoted';
    case BOOKED = 'Booked';
    case IN_REPAIR = 'In Repair';
    case COMPLETED = 'Completed';

    // Helper function for validation
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}