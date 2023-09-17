<?php

use App\Http\Livewire\Student\GetStudents;
use App\Http\Livewire\Student\StudentCourseReset;
use App\Http\Livewire\Student\UpdateStudentProfile;
use Illuminate\Support\Facades\Route;

//Routes
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('students', GetStudents::class)->name('students');

    // Route::prefix('student')->name('student.')->group(function () {
    //     Route::get('update/{std_logid}', UpdateStudentProfile::class)->name('update');
    //     Route::get('course_reset', StudentCourseReset::class)->name('course_reset');
    //     Route::get('lost_access', LostAccessComponent::class)->name('lost_access');
    // });
