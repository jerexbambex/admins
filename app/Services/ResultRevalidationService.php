<?php

namespace App\Services;

use App\Exports\ResultRevalidationExport;
use App\Models\Course;
use App\Models\CourseReg;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ResultRevalidationService
{
    static function getCourses(
        $admission_year,
        $dept_option_id,
        $programme_type,
        $level_id,
        $semester
    ) {
        $courses = [];
        if ($admission_year && $dept_option_id && $programme_type && $level_id && $semester) {
            $courses = Course::where('for_set', 'like', "%$admission_year%");
            $courses->whereStdcourse($dept_option_id);
            $courses->whereForCec($programme_type == 2 ? 1 : 0);
            $courses->whereLevels($level_id);
            $courses->whereSemester($semester);
            $courses = $courses->get(['thecourse_id', 'thecourse_code', 'thecourse_title']);
        }
        return $courses;
    }

    static function getCourse($course_id): ?Course
    {
        return Course::find($course_id);
    }


    static function getCourseRegs(
        $course_id,
        $session,
        $semester,
        $level_id
    ) {
        $course_regs = [];
        if ($course_id && $session && $semester && $level_id) {
            $course_regs = CourseReg::with(['student'])->whereThecourseId($course_id);
            $course_regs->whereCyearsession(explode('/', $session)[0]);
            $course_regs->whereCsemester($semester);
            $course_regs->where(function ($query) use ($level_id) {
                return $query->whereClevelId($level_id)
                    ->orWhere('rerun_level_id', $level_id);
            });
            $course_regs = $course_regs->get();
        }
        return $course_regs;
    }

    function download(
        $course_id,
        $session,
        $semester,
        $level_id
    ) {
        try {
            if (!$course_id || !$session || !$semester || !$level_id) {
                return session()->flash('error_toast', 'Unable to perform that action!');
            }

            $now = Carbon::now();

            session()->flash('success_toast', 'Download successful');

            return Excel::download(new ResultRevalidationExport(
                $course_id,
                $session,
                $semester,
                $level_id
            ), "result_revalidation_$now.xlsx");
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'An error occured!');
        }
    }
}
