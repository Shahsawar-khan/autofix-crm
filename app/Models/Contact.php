<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
     protected $fillable = ['name', 'phone', 'email'];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
