<?php

use App\Http\Livewire\Admission\AdmittedStudents;
use App\Http\Livewire\Admission\ChangeApplicantProgrammeTypeComponent;
use App\Http\Livewire\Admission\ChangeOfCourseBulkUploadComponent;
use App\Http\Livewire\Admission\DownloadTemplate;
use App\Http\Livewire\Admission\EditApplicantsComponent;
use App\Http\Livewire\Admission\InstitutionsComponent;
use App\Http\Livewire\Admission\StudentChangeOfCourseBulkUploadComponent;
use App\Http\Livewire\Admission\StudentComponent;
use App\Http\Livewire\Admission\StudentFilterComponent;
use App\Http\Livewire\Admission\UpdateStudentAdmissionDataComponent;
use App\Http\Livewire\Admission\UploadAdmittedApplicantsComponent;
use App\Http\Livewire\Admission\ViewApplicantsComponent;
use App\Http\Livewire\Admission\ViewStudentFileComponent;
use App\Http\Livewire\Dr\ApplicantCbtScoreComponent;
use App\Http\Livewire\Student\GetStudents;
use App\Http\Livewire\Student\UpdateStudentProfile;
use Illuminate\Support\Facades\Route;

/**
 * 
 * 
 * ADMISSION ROUTES
 * 
 * 
 */
//Routes
Route::get('/', StudentFilterComponent::class)->name('dashboard');
Route::get('applicants_score', ApplicantCbtScoreComponent::class)->name('upload_applicants_score');
Route::get('institutions', InstitutionsComponent::class)->name('institutions');

Route::prefix('applicants')->name('applicants.')->group(function () {
    Route::get('view', ViewApplicantsComponent::class)->name('view');
    Route::get('edit/{std_logid}', EditApplicantsComponent::class)->name('edit');
    Route::get('template', DownloadTemplate::class)->name('template');
    Route::get('change_prog_type', ChangeApplicantProgrammeTypeComponent::class)->name('change_prog_type');

    Route::get('change_of_course_upload', ChangeOfCourseBulkUploadComponent::class)->name('change_of_course_upload');
});

Route::prefix('admitted')->name('admitted.')->group(function () {
    Route::get('upload', UploadAdmittedApplicantsComponent::class)->name('upload');
    Route::get('students', AdmittedStudents::class)->name('students');
    Route::get('students/update/{param}', UpdateStudentAdmissionDataComponent::class)->name('students.update');
});

Route::get('students', GetStudents::class)->name('students');
Route::prefix('student')->name('student.')->group(function () {
    Route::get('view', StudentFilterComponent::class)->name('view');
    Route::get('update/{std_logid}', UpdateStudentProfile::class)->name('update');
    Route::get('change-of-course', StudentChangeOfCourseBulkUploadComponent::class)->name('change-of-course');
    Route::get('view/{fac_id?}/{dept_id?}/{do_id?}', StudentComponent::class)->name('view_stds');
    Route::get('download/file/{std_logid}', ViewStudentFileComponent::class)->name('download.file');

    Route::get('view_file/{file_ref_id?}/', function ($file_ref_id) {
        return view('exports.student.view_file', compact('file_ref_id'));
    })->name('view_file');
});
