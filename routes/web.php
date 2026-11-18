<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;

Route::get('/', function () {
    return redirect('/pipeline');
});

Route::get('/pipeline', [LeadController::class, 'board']);
Route::get('/reminders', [App\Http\Controllers\LeadController::class, 'reminders']);
