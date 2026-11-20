<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = ['lead_id', 'template_id', 'to_email', 'subject', 'body', 'status'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}