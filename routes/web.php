<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\EmailController;

Route::get('/', function () {
    return redirect('/pipeline');
});

Route::get('/pipeline', [LeadController::class, 'board']);
Route::get('/reminders', [App\Http\Controllers\LeadController::class, 'reminders']);
Route::get('/emails', [EmailController::class, 'indexLogs']);
Route::get('/templates', [EmailController::class, 'indexTemplates']);