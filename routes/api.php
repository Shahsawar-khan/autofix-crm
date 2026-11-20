<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\EmailController;

Route::get('/leads', [LeadController::class, 'index']);
Route::post('/leads', [LeadController::class, 'store']);
Route::patch('/leads/{lead}/status', [LeadController::class, 'updateStatus']);
Route::patch('/leads/{lead}/follow-up', [LeadController::class, 'updateFollowUp']);
// routes/api.php
Route::patch('/leads/{lead}/follow-up/complete', [App\Http\Controllers\LeadController::class, 'completeFollowUp']);

Route::get('/templates', [EmailController::class, 'getTemplates']);
Route::get('/leads/{lead}/emails', [EmailController::class, 'getLogs']);
Route::post('/emails/send', [EmailController::class, 'sendEmail']);