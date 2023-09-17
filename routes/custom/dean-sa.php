<?php

use App\Http\Livewire\Student\PaymentsList;
use App\Http\Livewire\Student\RegisteredList;
use App\Http\Livewire\Student\StatisticsComponent;
use Illuminate\Support\Facades\Route;

/**
 * 
 * 
 * DEAN - STUDENT AFFAIRS' ROUTES
 * X
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
