<?php

use App\Http\Controllers\ResultController;
use App\Http\Controllers\TestRunController;
use App\Http\Livewire\Dr\CourseStructureComponent;
use App\Http\Livewire\Dr\DepartmentalCodesComponent;
use App\Http\Livewire\FacultyDeansBaseComponent;
use App\Http\Livewire\GraduatingRequirementsComponent;
use App\Http\Livewire\HodsBaseComponent;
use App\Http\Livewire\Penalties\DeadStudentsComponent;
use App\Http\Livewire\Penalties\ExpulsionPenaltyComponent;
use App\Http\Livewire\Penalties\IndefiniteSuspensionPenaltyComponent;
use App\Http\Livewire\Penalties\ReinstatementComponent;
use App\Http\Livewire\Penalties\ReinstatementFromIndefinitePenaltyComponent;
use App\Http\Livewire\Penalties\SickPenaltyComponent;
use App\Http\Livewire\Penalties\SuspensionPenaltyComponent;
use App\Http\Livewire\ResultRevalidationComponent;
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
 * DIRECTOR ROUTES
 * 
 * 
 */

//Routes
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('students')->name('students.')->group(function () {
    Route::get('registered-list', RegisteredList::class)->name('registered');
    Route::get('tuition-list', PaymentsList::class)->name('tuition');
    Route::get('statistics', StatisticsComponent::class)->name('statistics');
});

Route::prefix('school')->name('school.')->group(function () {
    Route::get('dept_codes', DepartmentalCodesComponent::class)->name('dept_codes');
    Route::get('graduating_requirements', GraduatingRequirementsComponent::class)->name('graduating_requirements');
    Route::get('school_dates', SchoolDatesComponent::class)->name('school_dates');
    Route::get('course_structure', CourseStructureComponent::class)->name('course_structure');
    Route::get('hods', HodsBaseComponent::class)->name('hods');
    Route::get('faculty-deans', FacultyDeansBaseComponent::class)->name('faculty-deans');
});

Route::prefix('students/penalties')->name('student-penalties.')->group(function () {
    Route::get('suspension', SuspensionPenaltyComponent::class)->name('suspension');
    Route::get('suspension/indefinite', IndefiniteSuspensionPenaltyComponent::class)->name('indefinite-suspension');
    Route::get('expulsion', ExpulsionPenaltyComponent::class)->name('expulsion');
    Route::get('sick', SickPenaltyComponent::class)->name('sick');
    Route::get('death', DeadStudentsComponent::class)->name('death');
    Route::get('reinstatement/indefinite', ReinstatementFromIndefinitePenaltyComponent::class)->name('reinstatement-from-indefinite');
    Route::get('reinstatement/{penalty_id}', ReinstatementComponent::class)->name('reinstatement');
});


Route::prefix('results')->name('results.')->group(function () {
    Route::get('assigned_submitted', AssignedAndSubmittedReportComponent::class)->name('assigned_submitted');
    Route::get('unassigned', UnassignedCoursesReportComponent::class)->name('unassigned');
    Route::get('assigned_not_submitted', AssignedYetToSubmitReportComponent::class)->name('assigned_not_submitted');
    Route::get('processing', ResultProcessingComponent::class)->name('processing');

    Route::get('revalidation', ResultRevalidationComponent::class)->name('revalidation');

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
