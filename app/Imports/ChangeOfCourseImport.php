<?php

namespace App\Imports;

use App\Models\Applicant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class ChangeOfCourseImport implements ToCollection
{
    public $department_id, $faculty_id;

    public function __construct($faculty_id, $department_id)
    {
        $this->faculty_id = $faculty_id;
        $this->department_id = $department_id;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        try {
            $count = 0;
            $prog_type_id = auth()->user()->prog_type_id;
            foreach ($rows as $row) {
                $appno = $row[0];
                if ($appno) {
                    $applicant = Applicant::whereAppNo($appno);
                    if ($prog_type_id) $applicant->where('std_programmetype', $prog_type_id);
                    $applicant = $applicant->first();
                    if ($applicant) {
                        $course = DB::table('dept_options')->where('dept_id', $this->department_id)
                            ->where('prog_id', $applicant->stdprogramme_id)->first();
                        if ($course) {
                            $new_course_id = $course->do_id;
                            $log_id = $applicant->std_logid;
                            $initial_course_id = $applicant->stdcourse;
                            $status = 'pending';

                            DB::table('change_of_courses')->insert(compact('appno', 'log_id', 'initial_course_id', 'new_course_id', 'status'));
                            $count++;
                        }
                    }
                }
            }
            return session()->flash('success_alert', "$count Uploads Successful");
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'An error occured');
        }
    }
}
