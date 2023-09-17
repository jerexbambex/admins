<?php

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\TestRunController;
use App\Http\Controllers\UploadController;
use App\Http\Livewire\PrintCourseStructureComponent;
use App\Http\Livewire\SessionChangeComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/**
 * AUTH ROUTES
 */
Route::get('/', function () {
    //return view('welcome');
    return Redirect::to('/login');
});

Route::get('/register', function () {
    return Redirect::to('/login');
});

// Route::get('/del_lec_courses', [TestRunController::class, 'deleted_lec_courses']);


// Route::get('/migrate_database', function(){
//     Artisan::call('migrate');
//     Artisan::call('db:seed');
// });

/**
 * 
 * TEST RUNS 
 * 
 * 
 */

// Route::get('/set_student_sessions', [TestRunController::class, 'student_session']);
// Route::get('/update_student_status', [TestRunController::class, 'update_student_status']);
// Route::get('/del_school_fees_with_wrong_amount', [TestRunController::class, 'del_school_fees_with_wrong_amount']);
// Route::get('/fetch_back_payment', [TestRunController::class, 'fetch_back_payment']);
// Route::get('/session_error_fix', [TestRunController::class, 'session_error_fix']);
// Route::get('/no_account_std_logins_clear', [TestRunController::class, 'no_account_std_logins_clear']);
// Route::get('/students_wrong_matset_clear', [TestRunController::class, 'students_wrong_matset_clear']);
Route::get('/set_other_false_payments_pending', [TestRunController::class, 'set_other_false_payments_pending']);
Route::get('/false_payments_phisher', [TestRunController::class, 'false_payments_phisher']);
Route::get('/matric_number_generator', [TestRunController::class, 'matric_number_generator']);
// Route::get('/reset_short_matric_numbers', [TestRunController::class, 'reset_short_matric_numbers']);
// Route::get('/auto_push_unadmitted_to_cec', [TestRunController::class, 'auto_push_unadmitted_to_cec']);
Route::get('/get_hostel_payments_from_dom', [TestRunController::class, 'get_hostel_payments_from_dom']);
Route::get('/move_2020_admissions_to_portalaccess', [TestRunController::class, 'move_2020_admissions_to_portalaccess']);
Route::get('/student_name_correction', [TestRunController::class, 'student_name_correction']);
Route::get('/applicants_points_update', [TestRunController::class, 'applicants_points_update']);
Route::get('/applicants_points_update_2', [TestRunController::class, 'applicants_points_update_2']);
Route::get('/delete_multiple_admission_record_asc', [TestRunController::class, 'delete_multiple_admission_record_asc']);
Route::get('/delete_multiple_admission_record_desc', [TestRunController::class, 'delete_multiple_admission_record_desc']);


/**
 * 
 * 
 * DASHBOARD
 * 
 * 
 */

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', function (Request $request) {
        /** @var \App\Models\User $user */
        $user = $request->user();

        switch ($user->user_role()) {
            case 'rector':
                return redirect()->route('rector.dashboard');
            case 'hod':
                return redirect()->route('hod.dashboard');
            case 'lecturer':
                return redirect()->route('lecturer.dashboard');
            case 'dr':
                return redirect()->route('dr.dashboard');
            case 'bursary':
                return redirect()->route('bursary.dashboard');
            case 'admission':
                return redirect()->route('admission.dashboard');
            case 'corrector':
                return redirect()->route('corrector.dashboard');
            case 'director':
                return redirect()->route('director.dashboard');
            case 'cidm-user':
                return redirect()->route('cidm-user.dashboard');
            case 'cec-admin':
                return redirect()->route('cec-admin.dashboard');
            case 'dpp-admin':
                return redirect()->route('dpp-admin.dashboard');
            case 'exams-and-records':
                return redirect()->route('exams-and-records.dashboard');
            case 'dean-sa':
                return redirect()->route('dean-sa.dashboard');
            case 'faculty-dean':
                return redirect()->route('faculty-dean.dashboard');
            case 'revalidation-admin':
                return redirect()->route('revalidation-admin.dashboard');

            default:
                return abort(401);
        }
    })->name('dashboard');

    Route::get('change_session', SessionChangeComponent::class)->name('change_session');
    Route::get('course_structure/view', PrintCourseStructureComponent::class)->name('view_course_structure');
    Route::get('course_structure/print/{dept_id}/{prog_id}/{session}', function ($dept_id, $prog_id, $session) {
        return view('exports.course_structure', compact('dept_id', 'prog_id', 'session'));
    })->name('print_course_structure');

    Route::middleware('role:admission|dr')->group(function () {
        Route::post('applicants_score_upload', [UploadController::class, 'uploadApplicantsCbtScore'])->name('applicants_score_upload');
    });

    Route::get(
        'downloadStudentsDataReport/{fac_id?}/{dept_id?}/{opt_id?}/{level_id?}/{sess_id?}/{prog_id?}/{progtype_id?}/{param?}/{search_param?}',
        [TestRunController::class, 'downloadStudentsDataReport']
    )->name('downloadStudentsDataReport');

    Route::get(
        '/registered-list-by-faculty/{fac_id}/{level_id}/{sess_id}/{prog_id}/{progtype_id}/{semester_id}/{dept_id?}',
        [TestRunController::class, 'downloadRegisteredStudentsByFaculty']
    )->name('registered-students-by-faculty');

    Route::get(
        '/download-student-statistics/{statistics_type?}/{fac_id?}/{dept_id?}/{prog_id?}/{opt_id?}/{sess_id?}/{semester_id?}',
        [TestRunController::class, 'downloadStudentsStatistics']
    )->name('download-student-statistics');

    Route::get(
        '/tuition-list-by-faculty/{fac_id}/{level_id}/{sess_id}/{prog_id}/{progtype_id}/{semester_id}/{dept_id?}',
        [TestRunController::class, 'downloadStudentsTuitionByFaculty']
    )->name('students-tuition-by-faculty');

    //Admission | CEC Admin | DPP Admin enabled ROUTES
    Route::middleware(['role:admission|cec-admin|dpp-admin'])->group(function () {
        //Download Applicants
        Route::get(
            'download_applicants/{session?}/{faculty_id?}/{department_id?}/{prog_id?}/{prog_type_id?}/{adm_status?}/{search?}',
            [DownloadController::class, 'downloadApplicants']
        )->name('download_applicants');

        //Upload Admitted Applicants
        Route::post('upload_admitted_applicants', [UploadController::class, 'uploadAdmittedApplicants'])->name('upload_admitted_applicants');

        //Change of Course Bulk Upload
        Route::post('change_of_course_bulk_upload', [UploadController::class, 'changeOfCourseBulkUpload'])->name('change_of_course_bulk_upload');

        //Change of Course Bulk Upload
        Route::post('student_change_of_course_bulk_upload', [UploadController::class, 'studentChangeOfCourseBulkUpload'])->name('student_change_of_course_bulk_upload');

        //Download admission template data
        Route::get(
            'download_admission_template_data/{session_year}/{prog_id}/{prog_type_id}/{search?}',
            [DownloadController::class, 'download_admission_template_data']
        )->name('download_admission_template_data');

        //Download Accepted Migration
        Route::get(
            'download_migration_accepted',
            [DownloadController::class, 'download_migration_accepted_list']
        )->name('download_migration_accepted');

        //Download Students' Data
        Route::get(
            'download_students_data/{faculty}/{department}/{course}/{level}/{adm_year}/{programme}/{programme_type}/{search?}',
            [DownloadController::class, 'downloadStudentsData']
        )->name('download_students_data');

        Route::get(
            'download_students_clearance_report/{adm_year?}/{prog_id?}/{prog_type_id?}/{do_id?}/{department_id?}/{fac_id?}/{clear_status?}/{search?}/{date_from?}/{date_to?}',
            [DownloadController::class, 'downloadStudents']
        )->name('download_students');
    });

    Route::get(
        'download_student_registered_list/{fac_id?}/{dept_id?}/{opt_id?}/{level_id?}/{sess_id?}/{prog_id?}/{progtype_id?}/{semester_id?}',
        [DownloadController::class, 'download_student_registered_list']
    )->name('download_student_registered_list');

    Route::get(
        'download_student_payments_list/{fac_id?}/{dept_id?}/{opt_id?}/{level_id?}/{sess_id?}/{prog_id?}/{progtype_id?}/{semester_id?}',
        [DownloadController::class, 'download_student_payments_list']
    )->name('download_student_payments_list');
});
