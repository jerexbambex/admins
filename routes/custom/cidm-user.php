<?php

use App\Http\Livewire\Dr\CourseStructureComponent;
use Illuminate\Support\Facades\Route;

/**
 * 
 * 
 * BURSARY ROUTES
 * 
 * 
 */

//Routes
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('course_structure', CourseStructureComponent::class)->name('course_structure');
