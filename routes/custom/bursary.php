<?php

use App\Http\Livewire\Bursary\HostelManualPaymentsComponent;
use App\Http\Livewire\Bursary\StudentPaymentComponent;
use Illuminate\Support\Facades\Route;

/**
 * 
 * 
 * BURSARY ROUTES
 * 
 * 
 */

//Routes
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('students')->name('students.')->group(function () {
    Route::get('payment', StudentPaymentComponent::class)->name('payment');
});

Route::get('manual-hostel-payment', HostelManualPaymentsComponent::class)->name('manual-hostel-payment');