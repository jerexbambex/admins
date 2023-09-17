<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/** Login Route */
Route::post('/login', [AuthController::class, 'userLogin']);





/**
 * 
 * 
 * GENERAL ROUTES
 * 
 * 
 */
Route::middleware(['auth:sanctum'])->group(function () {

    /** Get User Data Rute */
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    /** Applicants routes */
    Route::prefix('applicants')->group(function () {
        Route::get('all', [ApplicantController::class, 'fetchAll']);
        Route::get('admitted', [StudentController::class, 'getAdmittedStudents']);
        Route::get('password/reset/{std_id}', [ApplicantController::class, 'admitApplicant']);
    });

    /** Students routes */
    Route::prefix('students')->group(function () {
        Route::get('all', [StudentController::class, 'getStudentsData']);
    });

    /** Log Out Route */
    Route::post('/user/logout', [AuthController::class, 'logOut']);
});

/**
 * 
 * 
 * ADMISSION ROUTES
 * 
 * 
 */
Route::middleware(['auth:sanctum', 'admission'])->group(function () {
    /** Applicants routes */
    Route::prefix('applicants')->group(function () {
        Route::post('admit/{std_id}', [ApplicantController::class, 'admitApplicant']);
        Route::post('unadmit/{pid}', [StudentController::class, 'revertAdmissions']);
    });

    /** Students routes */
    Route::prefix('students')->group(function () {
        Route::post('clear/{std_id}', [StudentController::class, 'clear']);
        Route::post('unclear/{std_id}', [StudentController::class, 'unClear']);
    });
});