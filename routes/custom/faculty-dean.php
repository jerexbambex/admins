<?php

use App\Http\Controllers\ResultController;
use App\Http\Livewire\Rector\Students\StudentsHistoryBaseComponent;
use App\Http\Livewire\Results\AssignedAndSubmittedReportComponent;
use App\Http\Livewire\Results\AssignedYetToSubmitReportComponent;
use App\Http\Livewire\Results\ResultProcessingComponent;
use App\Http\Livewire\Results\UnassignedCoursesReportComponent;
use App\Http\Livewire\SchoolDatesComponent;
use App\Http\Livewire\Student\PaymentsList;
use App\Http\Livewire\Student\RegisteredList;
use App\Http\Livewire\Student\StatisticsComponent;
use Illuminate\Support\Facades\Route;

/**
 * 
 * 
 * FACULTY DEANS' ROUTES
 * 
 * 
 */

//Routes
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Route::prefix('students')->name('students.')->group(function () {
//     Route::get('history/{param}', StudentsHistoryBaseComponent::class)->name('history');
// });

// Route::get('school_dates', SchoolDatesComponent::class)->name('school_dates');

Route::prefix('results')->name('results.')->group(function () {
    Route::get('assigned_submitted', AssignedAndSubmittedReportComponent::class)->name('assigned_submitted');
    Route::get('unassigned', UnassignedCoursesReportComponent::class)->name('unassigned');
    Route::get('assigned_not_submitted', AssignedYetToSubmitReportComponent::class)->name('assigned_not_submitted');
    Route::get('processing', ResultProcessingComponent::class)->name('processing');

    Route::get('semester_result/{set}/{encoded_session}/{semester_id}/{prog_id}/{level_id}/{prog_type_id}/{option_id}', [
        ResultController::class,
        'print_semester_result'
    ])->name('semester-result');

    Route::get('running_list/{set}/{encoded_session}/{semester_id}/{prog_id}/{level_id}/{prog_type_id}/{option_id}', [
        ResultController::class,
        'print_running_list'
    ])->name('running-list');

    Route::get('graduating_semester_result/{set}/{encoded_session}/{semester_id}/{prog_id}/{level_id}/{prog_type_id}/{option_id}', [
        ResultController::class,
        'print_graduating_semester_result'
    ])->name('graduating-semester-result');

    Route::get('graduating_running_list/{set}/{encoded_session}/{semester_id}/{prog_id}/{level_id}/{prog_type_id}/{option_id}', [
        ResultController::class,
        'print_graduating_running_list'
    ])->name('graduating-running-list');
});

Route::prefix('students')->name('students.')->group(function () {
    Route::get('registered-list', RegisteredList::class)->name('registered');
    // Route::get('tuition-list', PaymentsList::class)->name('tuition');
    // Route::get('statistics', StatisticsComponent::class)->name('statistics');
});
