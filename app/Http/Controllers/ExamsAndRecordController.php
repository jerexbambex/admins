<?php

namespace App\Http\Controllers;

use App\Models\BosLog;
use App\Services\ExamsAndRecordService;
use Illuminate\Http\Request;

class ExamsAndRecordController extends Controller
{
    function certificate(BosLog $bos_log, $student_log_id = null)
    {
        $students = [];

        if (!$bos_log) {
            return abort(403);
        }

        $record = ExamsAndRecordService::resultData($bos_log, $student_log_id);
        if ($record === false) {
            return abort(403);
        }

        foreach ($record as $student) {
            if ($student->canGraduate($bos_log->session, $bos_log->semester_id, $bos_log->level_id)) {
                $cgpa = $student->get_result_gp(
                    $bos_log->session,
                    $bos_log->semester_id,
                    $bos_log->level_id,
                    true
                );

                $students[] = [
                    'course' => $student->course_name,
                    'full_name'  =>  $student->full_name,
                    'grade' =>  grade_points($cgpa),
                    'point' =>  $cgpa,
                    'session_end'  =>  explode('/', $bos_log->session)[1]
                ];
            }
        }

        return view('exports.exams-and-record.certificate', compact('students'));
    }

    function notification_of_result(BosLog $bos_log, $student_log_id = null)
    {
        $students = [];

        if (!$bos_log) {
            return abort(403);
        }

        $record = ExamsAndRecordService::resultData($bos_log, $student_log_id);
        if ($record === false) {
            return abort(403);
        }

        foreach ($record as $student) {
            if ($student->canGraduate($bos_log->session, $bos_log->semester_id, $bos_log->level_id)) {
                $cgpa = $student->get_result_gp(
                    $bos_log->session,
                    $bos_log->semester_id,
                    $bos_log->level_id,
                    true
                );

                $students[] = [
                    'full_name'  =>  $student->full_name,
                    'matric_number' => $student->matric_no,
                    'course' => $student->course_name,
                    'department' => $student->department_name,
                    'programme' => $student->programme_name,
                    'faculty' => $student->faculty_name,
                    'grade' =>  grade_points($cgpa),
                    'point' =>  $cgpa,
                    'session'   =>  $bos_log->session,
                    'session_end'  => explode('/', $bos_log->session)[1]
                ];
            }
        }

        return view('exports.exams-and-record.notification_of_result', compact('students', 'bos_log'));
    }
}
