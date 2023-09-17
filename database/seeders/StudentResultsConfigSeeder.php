<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\StudentResultsConfig;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentResultsConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // foreach(Department::all(['departments_id']) as $dept){
            StudentResultsConfig::create([
                'session_year'  =>  '2020',
                // 'department_id' =>  $dept->departments_id,
                'lecturer_upload_start_date' => now(),
                'lecturer_upload_end_date' => Carbon::now()->addMonth()
            ]);
        // }
    }
}
