<?php

use App\Http\Controllers\ResultController;
use App\Http\Controllers\UploadController;
use App\Http\Livewire\Lecturer\CourseAssignedComponent;
use App\Http\Livewire\Lecturer\LecturerEditResultsComponent;
use App\Http\Livewire\Lecturer\LecturerScoreSheet;
use App\Http\Livewire\Lecturer\LecturerUploadResultsComponent;
use App\Http\Livewire\Lecturer\LecturerViewResultsComponent;
use Illuminate\Support\Facades\Route;

/**
 * 
 * 
 * LECTURER ROUTES
 * 
 * 
 */

//Routes

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('courses', CourseAssignedComponent::class)->name('courses');
Route::get('scoreSheet/{course?}/{id}', LecturerScoreSheet::class)->name('scoreSheet');

Route::prefix('result')->name('result.')->group(function () {

    Route::get('scoresheet/{encoded_session?}/{course_id?}/{lec_course_id?}', function ($encoded_session, $course_id, $lec_course_id) {
        return view('exports.prints.scoresheet', compact('encoded_session', 'course_id', 'lec_course_id'));
    })->name('scoresheet');

    Route::get('/blank-sheet/{encoded_session?}/{course_id?}/{lec_course_id?}', function ($encoded_session, $course_id, $lec_course_id) {
        return view('exports.prints.blank-scoresheet', compact('encoded_session', 'course_id', 'lec_course_id'));
    })->name('blank-sheet');

    Route::get('/upload/view', LecturerUploadResultsComponent::class)->name('upload-view');
    Route::post('/upload', [ResultController::class, 'uploadResult'])->name('upload');
    Route::get('/view', LecturerViewResultsComponent::class)->name('view');
    Route::get('/edit/{result_id}', LecturerEditResultsComponent::class)->name('edit');
});
