<?php

use App\Http\Controllers\UploadController;
use App\Http\Livewire\Bursary\HostelManualPaymentsComponent;
use App\Http\Livewire\Dr\AllHodsViewComponent;
use App\Http\Livewire\Dr\ApplicantCbtScoreComponent;
use App\Http\Livewire\Dr\CourseStructureComponent;
use App\Http\Livewire\Dr\DepartmentalCodesComponent;
use App\Http\Livewire\Dr\ExamDatesComponent;
use App\Http\Livewire\Payments\PaymentHistoryCountComponent;
use App\Http\Livewire\Student\ClearMultipleAspirantAccount;
use App\Http\Livewire\Student\GetPortal;
use App\Http\Livewire\Student\GetStudents;
use App\Http\Livewire\Student\LostAccessComponent;
use App\Http\Livewire\Student\PaymentsList;
use App\Http\Livewire\Student\RegisteredList;
use App\Http\Livewire\Student\StatisticsComponent;
use App\Http\Livewire\Student\StudentCourseReset;
use App\Http\Livewire\Student\StudentNotificationsComponent;
use App\Http\Livewire\Student\UpdatePortalAccess;
use App\Http\Livewire\Student\UpdateStudentProfile;
use Illuminate\Support\Facades\Route;

/**
 * 
 * 
 * DR ROUTES
 * 
 * 
 */

//Routes
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('students', GetStudents::class)->name('students');
Route::get('portal', GetPortal::class)->name('portal');

Route::prefix('student')->name('student.')->group(function () {
    // Route::get('update/{std_logid}', UpdateStudentProfile::class)->name('update');
    // Route::get('course_reset', StudentCourseReset::class)->name('course_reset');

    Route::get('multiple_aspirants', ClearMultipleAspirantAccount::class)->name('multiple_aspirants');
    Route::get('notifications', StudentNotificationsComponent::class)->name('notifications');
    // Route::get('lost_access', LostAccessComponent::class)->name('lost_access');
});
// Route::get('/portal/update/{pid}', UpdatePortalAccess::class)->name('portal.update');

Route::get('set_exam_dates', ExamDatesComponent::class)->name('set_exam_dates');
Route::get('applicants_score', ApplicantCbtScoreComponent::class)->name('upload_applicants_score');

Route::get('all_hods', AllHodsViewComponent::class)->name('all_hods');

// Route::get('course_structure', CourseStructureComponent::class)->name('course_structure');
Route::get('dept_codes', DepartmentalCodesComponent::class)->name('dept_codes');

Route::prefix('payments')->name('payments.history.')->group(function () {
    Route::get('/payment_counts', PaymentHistoryCountComponent::class)->name('count');
    // Route::get('manual-hostel-payment', HostelManualPaymentsComponent::class)->name('manual-hostel-payment');
});

Route::prefix('students')->name('students.')->group(function () {
    Route::get('registered-list', RegisteredList::class)->name('registered');
    Route::get('tuition-list', PaymentsList::class)->name('tuition');
    Route::get('statistics', StatisticsComponent::class)->name('statistics');
});
