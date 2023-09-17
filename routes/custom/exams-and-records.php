<?php

use App\Http\Controllers\ExamsAndRecordController;
use App\Http\Livewire\ExamAndRecordsDashboard;
use App\Http\Livewire\ExamsAndRecord\StudentCertificateComponent;
use App\Http\Livewire\ExamsAndRecord\StudentNORComponent;
use Illuminate\Support\Facades\Route;

//Routes
Route::get('/', ExamAndRecordsDashboard::class)->name('dashboard');
Route::get('/certificate', StudentCertificateComponent::class)->name('certificate');
Route::get('/notification_of_result', StudentNORComponent::class)->name('notification_of_result');

Route::prefix('results')->name('results.')->group(function () {
    Route::get('certificate/{bos_log_id}/{student_log_id?}', [
        ExamsAndRecordController::class, 'certificate'
    ])->name('certificate');

    Route::get('notification_of_result/{bos_log_id}/{student_log_id?}', [
        ExamsAndRecordController::class, 'notification_of_result'
    ])->name('notification_of_result');
});
