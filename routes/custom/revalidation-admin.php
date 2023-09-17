<?php

use App\Http\Livewire\Admission\AdmittedStudents;
use App\Http\Livewire\Admission\UpdateStudentAdmissionDataComponent;
use App\Http\Livewire\RevalidationPaymentRecordComponent;
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

Route::prefix('admitted')->name('admitted.')->group(function () {
    Route::get('students', AdmittedStudents::class)->name('students');
    Route::get('students/update/{param}', UpdateStudentAdmissionDataComponent::class)->name('students.update');
});
Route::get('payment_record', RevalidationPaymentRecordComponent::class)->name('payment_record');
