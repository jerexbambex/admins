<?php

use App\Http\Controllers\ResultController;
use App\Http\Livewire\Dr\DepartmentalCodesComponent;
use App\Http\Livewire\GraduatingRequirementsComponent;
use App\Http\Livewire\Hod\AddLecturers;
use App\Http\Livewire\Hod\AssignLecturerToCourseComponent;
use App\Http\Livewire\Hod\DownloadResultComponent;
use App\Http\Livewire\Hod\FinalResultBosComponent;
use App\Http\Livewire\Hod\FinalResultGraduatingComponent;
use App\Http\Livewire\Hod\FinalResultVettedComponent;
use App\Http\Livewire\Hod\HodScoreSheetComponent;
use App\Http\Livewire\Hod\HodViewResultsComponent;
use App\Http\Livewire\Hod\Lecturers;
use App\Http\Livewire\Hod\SubmitResultComponent;
use App\Http\Livewire\Hod\ViewCoursesComponent;
use App\Http\Livewire\Student\PaymentsList;
use App\Http\Livewire\Student\RegisteredList;
use App\Http\Livewire\Student\StatisticsComponent;
use Illuminate\Support\Facades\Route;

/**
 * 
 * 
 * HOD ROUTES
 * 
 * 
 */

//Routes

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('lecturer/{action}/{param?}', AddLecturers::class)->name('lecturer');
Route::get('assignCourses/{course}', AssignLecturerToCourseComponent::class)->name('assignCourses');
Route::get('lecturers', Lecturers::class)->name('lecturers');

/**
 * {param} = ['view', 'assign', 'scoresheet']
 */
Route::get('courses/{param}', ViewCoursesComponent::class)->name('courses');

Route::get('scoreSheet/{course?}', HodScoreSheetComponent::class)->name('scoreSheet');
Route::get('dept_codes', DepartmentalCodesComponent::class)->name('dept_codes');
Route::get('graduating_requirements', GraduatingRequirementsComponent::class)->name('graduating_requirements');

Route::prefix('results')->name('results.')->group(function () {
    Route::get('view', HodViewResultsComponent::class)->name('view');

    Route::get('scoresheet/{encoded_session}/{course_id?}/{lec_course_id?}', [
        ResultController::class,
        'print_scoresheet'
    ])->name('scoresheet');


    // Results presentation
    Route::get('semester_result/{set}/{encoded_session}/{semester_id}/{prog_id}/{level_id}/{prog_type_id}/{option_id}/{bos_log_id?}', [
        ResultController::class,
        'print_semester_result'
    ])->name('semester-result');

    Route::get('running_list/{set}/{encoded_session}/{semester_id}/{prog_id}/{level_id}/{prog_type_id}/{option_id}/{bos_log_id?}', [
        ResultController::class,
        'print_running_list'
    ])->name('running-list');

    Route::get('semester_result_vetter/{set}/{encoded_session}/{semester_id}/{prog_id}/{level_id}/{prog_type_id}/{option_id}/{bos_log_id?}', [
        ResultController::class,
        'print_semester_result_vetter'
    ])->name('semester-result-vetter');

    Route::get('running_list_vetter/{set}/{encoded_session}/{semester_id}/{prog_id}/{level_id}/{prog_type_id}/{option_id}/{bos_log_id?}', [
        ResultController::class,
        'print_running_list_vetter'
    ])->name('running-list-vetter');

    Route::get('semester_result_bos/{set}/{encoded_session}/{semester_id}/{prog_id}/{level_id}/{prog_type_id}/{option_id}/{bos_log_id?}', [
        ResultController::class,
        'print_semester_result_bos'
    ])->name('semester-result-bos');

    Route::get('running_list_bos/{set}/{encoded_session}/{semester_id}/{prog_id}/{level_id}/{prog_type_id}/{option_id}/{bos_log_id?}', [
        ResultController::class,
        'print_running_list_bos'
    ])->name('running-list-bos');

    Route::get('graduating_semester_result/{set}/{encoded_session}/{semester_id}/{prog_id}/{level_id}/{prog_type_id}/{option_id}/{bos_log_id?}', [
        ResultController::class,
        'print_graduating_semester_result'
    ])->name('graduating-semester-result');

    Route::get('graduating_running_list/{set}/{encoded_session}/{semester_id}/{prog_id}/{level_id}/{prog_type_id}/{option_id}/{bos_log_id?}', [
        ResultController::class,
        'print_graduating_running_list'
    ])->name('graduating-running-list');
    // End of result presentation

    Route::get('download', DownloadResultComponent::class)->name('download');

    Route::get('submission', SubmitResultComponent::class)->name('submission');

    Route::prefix('final')->name('final.')->group(function () {
        Route::get('vetted', FinalResultVettedComponent::class)->name('vetted');
        Route::get('bos', FinalResultBosComponent::class)->name('bos');
        Route::get('graduating', FinalResultGraduatingComponent::class)->name('graduating');
    });
});

Route::prefix('students')->name('students.')->group(function () {
    Route::get('registered-list', RegisteredList::class)->name('registered');
    Route::get('tuition-list', PaymentsList::class)->name('tuition');
    Route::get('statistics', StatisticsComponent::class)->name('statistics');
});
