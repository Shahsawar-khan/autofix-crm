<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;

Route::get('/leads', [LeadController::class, 'index']);
Route::post('/leads', [LeadController::class, 'store']);
Route::patch('/leads/{lead}/status', [LeadController::class, 'updateStatus']);
Route::patch('/leads/{lead}/follow-up', [LeadController::class, 'updateFollowUp']);
// routes/api.php
Route::patch('/leads/{lead}/follow-up/complete', [App\Http\Controllers\LeadController::class, 'completeFollowUp']);