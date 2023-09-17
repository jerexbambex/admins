<?php

namespace App\Services;

use App\Models\User;
use App\Models\Level;
use App\Models\BosLog;
use App\Models\Course;
use App\Models\Student;
use App\Models\DeptOption;
use App\Models\StudentResult;
use Illuminate\Database\Query\JoinClause;

class ResultService
{

    /**
     * Summary of getStudents
     * @param string $session
     * @param int $set
     * @param int $semester_id
     * @param int $level_id
     * @param int $prog_id
     * @param int $prog_type_id
     * @param int $dept_id
     * @param int $option_id
     * @param \App\Models\BosLog|null $bos_log
     * @return mixed
     */
    static function getStudents(
        $session,
        $set,
        $semester_id,
        $level_id,
        $prog_id,
        $prog_type_id,
        $dept_id,
        $option_id,
        ?BosLog $bos_log = null,
        ?Int $student_log_id = null
    ) {
        $app_session = explode('/', $session)[0];

        $semester = SEMESTERS[$semester_id];

        $students = Student::select([
            'stdprofile.*', 'student_sessions.log_id',
            'student_sessions.deleted_at', 'student_sessions.session'
        ])
            ->join(
                'student_sessions',
                function (JoinClause $join) use (
                    $session,
                    $semester_id,
                    $level_id,
                    $prog_id,
                    $set,
                    $prog_type_id
                ) {
                    $join->on('student_sessions.log_id', '=', 'stdprofile.std_logid')
                        ->whereRaw("student_sessions.session = '$session'")
                        ->whereRaw("student_sessions.semester = $semester_id")
                        ->whereRaw("student_sessions.level_id = $level_id")
                        ->whereRaw("student_sessions.prog_id = $prog_id")
                        ->whereRaw("student_sessions.admission_year = $set")
                        ->whereRaw("student_sessions.prog_type_id = $prog_type_id")
                        ->whereRaw('(student_sessions.deleted_at is null or student_sessions.deleted_at is not null)');
                }
            )
            ->join(
                'stdtransaction',
                function (JoinClause $join) use (
                    $level_id,
                    $app_session,
                    $semester
                ) {
                    $join->on('stdtransaction.log_id', '=', 'stdprofile.std_logid')
                        ->whereRaw("stdtransaction.levelid = $level_id")
                        ->whereRaw("stdtransaction.trans_year = '$app_session'")
                        ->whereRaw("stdtransaction.trans_semester like '%$semester%'")
                        ->whereRaw("stdtransaction.trans_name like '%school%'");
                }
            )
            ->whereRaw("stdprofile.stddepartment_id = $dept_id")
            ->whereRaw("stdprofile.stdcourse = $option_id")
            ->whereRaw("stdprofile.std_admyear = $set");

        if ($prog_type_id) {
            $students->where('stdprofile.stdprogrammetype_id', $prog_type_id);
        }

        if ($bos_log) {
            $students->join(
                'bos_logs',
                function (JoinClause $join) use (
                    $session,
                    $semester_id,
                    $level_id,
                    $prog_id,
                    $set,
                    $prog_type_id,
                    $bos_log
                ) {
                    $join->on('bos_logs.option_id', '=', 'stdprofile.stdcourse')
                        ->whereRaw("bos_logs.session = '$session'")
                        ->whereRaw("bos_logs.semester_id = $semester_id")
                        ->whereRaw("bos_logs.level_id = $level_id")
                        ->whereRaw("bos_logs.prog_id = $prog_id")
                        ->whereRaw("bos_logs.adm_year = $set")
                        ->whereRaw("bos_logs.prog_type_id = $prog_type_id")
                        ->whereRaw("bos_logs.bos_number = '$bos_log->bos_number'")
                        ->whereRaw("bos_logs.presentation = '$bos_log->presentation'");
                }
            );
        } else {
            $previousBosLog = BosLog::whereSession($session)->whereSemesterId($semester_id)
                ->whereLevelId($level_id)->whereProgId($prog_id)->whereAdmYear($set)
                ->whereProgTypeId($prog_type_id)
                ->orderByDesc('id')->first();
            if ($previousBosLog) {
                $students->whereRaw("stdprofile.std_logid not in (SELECT log_id from student_results where presentation != 0 and bos_number = '$previousBosLog->bos_number')");
            }
        }

        if ($student_log_id) {
            $students->whereLogId($student_log_id);
        }

        $students->groupby(['stdprofile.std_logid', 'student_sessions.log_id', 'student_sessions.session', 'student_sessions.deleted_at']);
        $students->orderBy('stdprofile.matric_no', 'asc');
        // dd($students->toSql());
        return $students->get([
            'std_id', 'std_logid', 'matric_no', 'surname',
            'firstname', 'othernames', 'matset'
        ]);
    }

    /**
     * Summary of getPresentation
     * @param int $option_id
     * @param int $set
     * @param string $session
     * @param int $semester
     * @param int $level_id
     * @param int $prog_type_id
     * @return int
     */
    static function getPresentation(
        $option_id,
        $set,
        $session,
        $semester,
        $level_id,
        $prog_type_id
    ) {
        $presentation = 1;
        $filter = compact(
            'session',
            'semester',
            'level_id',
            'prog_type_id',
        );

        $prev_result = StudentResult::selectRaw('student_results.presentation')
            ->join('stdprofile', function (JoinClause $join) use ($set, $option_id) {
                $join->on('stdprofile.std_logid', '=', 'student_results.log_id');
                $join->where('stdprofile.std_admyear', $set)->where('stdprofile.stdcourse', $option_id);
            })
            ->where($filter)
            ->orderByDesc('presentation')->first();

        if ($prev_result) {
            $presentation = $prev_result->presentation + 1;
        }
        return (int) $presentation;
    }

    /**
     * Summary of result_data
     * @param int $set
     * @param string $encoded_session
     * @param int $semester_id
     * @param int $prog_id
     * @param int $level_id
     * @param int $prog_type_id
     * @param int $option_id
     * @param int $bos_log_id
     * @return \Illuminate\Http\RedirectResponse|array|mixed
     */
    static function result_data(
        $set,
        $encoded_session,
        $semester_id,
        $prog_id,
        $level_id,
        $prog_type_id,
        $option_id,
        $bos_log_id = 0
    ) {
        if (!auth()->user()) {
            return redirect()->route('dashboard');
        }
        $bos_log = BosLog::find($bos_log_id);
        $user = User::find(auth()->user()->id);
        $option = DeptOption::find($option_id);
        if (!$user || !$option || (!$bos_log && $bos_log_id)) {
            return abort(401);
        }

        $session = base64_decode($encoded_session);
        $app_session = explode('/', $session)[0];
        $semester = '';
        if ($semester_id == 1) {
            $semester = 'First';
        } elseif ($semester_id == 2) {
            $semester = 'Second';
        } elseif ($semester_id == 3) {
            $semester = 'Third';
        }
        $bos_number = '';
        $hasPresentation = false;
        if ($bos_log) {
            $hasPresentation = true;

            $bos_number = $bos_log->bos_number;

            if (
                $bos_log->option_id != $option_id ||
                $bos_log->adm_year != $set ||
                $bos_log->session != $session ||
                $bos_log->level_id != $level_id ||
                $bos_log->semester_id != $semester_id ||
                $bos_log->prog_id != $prog_id ||
                $bos_log->prog_type_id != $prog_type_id
            ) {
                return abort(401);
            }
        }

        $department = null;
        if ($user->hasRole('rector') || $user->hasRole('director') || $user->hasRole('faculty-dean')) {
            $department = $option->department;
        } elseif ($user->hasRole('hod')) {
            $department = $user->department;
        } else {
            return redirect()->route('dashboard');
        }

        $presentation = 1;
        if ($hasPresentation) {
            $presentation = $bos_log->presentation;
        } else {
            $pesentation = self::getPresentation(
                $option_id,
                $set,
                $session,
                $semester,
                $level_id,
                $prog_type_id
            );
        }

        $level = Level::find($level_id);
        $faculty = $department->faculty;
        $for_cec = 0;
        if ($prog_type_id == 2) {
            $for_cec = 1;
        }

        $courses = Course::whereStdcourse($option_id)
            ->whereLevels($level_id)
            ->whereSemester(SEMESTERS[$semester_id])
            ->whereForCec($for_cec)
            ->whereRaw("for_set like '%$set%'")
            ->get();

        $students = self::getStudents(
            $session,
            $set,
            $semester_id,
            $level_id,
            $prog_id,
            $prog_type_id,
            $department->departments_id,
            $option_id,
            $bos_log
        );

        $total_results_presented = [];
        $total_results_presented[] = self::TotalPresented(
            $department->departments_id,
            $option_id,
            $set,
            $session,
            $level->level_id,
            $semester_id,
            $prog_id,
            $prog_type_id,
            $hasPresentation ?
                "student_results.presentation < $bos_log->presentation" :
                'student_results.presentation != 0'
        );
        $total_results_presented[] = self::TotalPresented(
            $department->departments_id,
            $option_id,
            $set,
            $session,
            $level->level_id,
            $semester_id,
            $prog_id,
            $prog_type_id,
            $hasPresentation ?
                "student_results.presentation = $bos_log->presentation" :
                'student_results.presentation = 0'
        );

        // $all_students = Student::select(['stdprofile.*', 'student_sessions.log_id', 'student_sessions.deleted_at', 'student_sessions.session'])
        //     ->join('student_sessions', 'student_sessions.log_id', 'stdprofile.std_logid')
        //     ->whereRaw("student_sessions.prog_id = $prog_id")
        //     ->whereRaw("student_sessions.admission_year = $set")
        //     ->whereRaw("student_sessions.prog_type_id = $prog_type_id")
        //     ->whereRaw('(student_sessions.deleted_at is null or student_sessions.deleted_at is not null)')
        //     ->whereRaw("stdprofile.stddepartment_id = $department->departments_id")
        //     ->whereRaw("stdprofile.stdcourse = $option_id")
        //     ->whereRaw("stdprofile.std_admyear = $set");
        // if ($prog_type_id) {
        //     $all_students->where('stdprofile.stdprogrammetype_id', $prog_type_id);
        // }
        // $all_students->groupby(['stdprofile.std_logid', 'student_sessions.log_id', 'student_sessions.session', 'student_sessions.deleted_at']);
        // $all_students = $all_students->get([
        //     'std_id', 'std_logid', 'matric_no', 'surname',
        //     'firstname', 'othernames', 'matset'
        // ]);
        // $total_students = count($all_students->toArray());
        $total_students = Student::whereStdcourse($option_id)->whereStdAdmyear($set)->count();

        $grand_total = array_sum($total_results_presented);

        return compact(
            'department',
            'faculty',
            'option',
            'session',
            'level',
            'courses',
            'total_results_presented',
            'total_students',
            'grand_total',
            'students',
            'bos_number',
            'hasPresentation',
            'presentation'
        );
    }

    /**
     * Summary of TotalPresented
     * @param int $department_id
     * @param int $option_id
     * @param int $set
     * @param string $session
     * @param int $level_id
     * @param int $semester_id
     * @param int $prog_id
     * @param int $prog_type_id
     * @param string $clause
     * @return int
     */
    static function TotalPresented(
        $department_id,
        $option_id,
        $set,
        $session,
        $level_id,
        $semester_id,
        $prog_id,
        $prog_type_id,
        $clause
    ) {
        $app_session = explode('/', $session)[0];
        $semester = SEMESTERS[$semester_id];

        // dd(func_get_args(), $app_session, $semester);
        return count(
            StudentResult::selectRaw('DISTINCT student_results.log_id')
                ->join('stdprofile', function (JoinClause $join)  use ($department_id, $option_id, $set) {
                    $join->on('stdprofile.std_logid', '=', 'student_results.log_id')
                        ->whereRaw("stdprofile.stddepartment_id = $department_id")
                        ->whereRaw("stdprofile.stdcourse = $option_id")
                        ->whereRaw("stdprofile.std_admyear = $set")
                        ->whereRaw("stdprofile.status = 'active'");
                })
                ->join('course_reg', function (JoinClause $join) use ($level_id, $app_session, $semester) {
                    $join->on('course_reg.log_id', '=', 'stdprofile.std_logid')
                        ->whereRaw("course_reg.clevel_id = $level_id")
                        ->whereRaw("course_reg.cyearsession = $app_session")
                        ->whereRaw("course_reg.csemester like '%$semester%'");
                })
                ->join('student_sessions', function (JoinClause $join) use (
                    $session,
                    $semester_id,
                    $level_id,
                    $prog_id,
                    $prog_type_id,
                    $set
                ) {
                    $join->on('student_sessions.log_id', '=', 'stdprofile.std_logid')
                        ->whereRaw('(student_sessions.deleted_at is null or student_sessions.deleted_at is not null)')
                        ->whereRaw("student_sessions.session = '$session'")
                        ->whereRaw("student_sessions.semester = $semester_id")
                        ->whereRaw("student_sessions.level_id = $level_id")
                        ->whereRaw("student_sessions.prog_id = $prog_id")
                        ->whereRaw("student_sessions.prog_type_id = $prog_type_id")
                        ->whereRaw("student_sessions.admission_year = $set");
                })
                ->join('stdtransaction', function (JoinClause $join) use ($app_session, $semester, $level_id) {
                    $join->on('stdtransaction.log_id', '=', 'stdprofile.std_logid')
                        ->whereRaw("stdtransaction.trans_year = '$app_session'")
                        ->whereRaw("stdtransaction.trans_semester like '%$semester%'")
                        ->whereRaw("stdtransaction.trans_name like '%school%'")
                        ->whereRaw("stdtransaction.levelid = $level_id");
                })
                ->whereRaw("student_results.level_id = $level_id")
                ->whereRaw("student_results.prog_type_id = $prog_type_id")
                ->whereRaw("student_results.session = '$session'")
                ->whereRaw("student_results.semester = '$semester_id'")
                ->whereRaw($clause)
                ->groupByRaw('student_results.log_id, student_results.course_id')
                // ->toSql()
                ->get()
                ->toArray(),
        );
    }

    static function addStudentSemesterResult(
        Student $student,
        $courses,
        $session,
        $set,
        $semester_id,
        $level_id,
        $graduating = false,
        $is_summary = false
    ) {
        $student_arr = [];
        if ($student) {
            $student_arr['matric_no']  = $student->matric_no;
            $student_arr['full_name']  = $student->full_name;
            $results = $grades = [];
            foreach ($courses as $course) {
                $hasResult = $student->hasResult($session, $course->thecourse_id);
                $result = $student->result($session, $course->thecourse_id);

                if ($result && $hasResult) {
                    $total = $result->total;
                    $results[] = $total;
                    if ($result->status == 'active') $grades[] = grade($total);
                    else $grades[] = $result->status;
                } else {
                    $grades[] = '';
                    $results[] = '';
                }

                $student_arr['results'] = $results;
                $student_arr['grades'] = $grades;
            }

            //Outstanding courses
            $outstanding_courses = $student->awaiting_list_string($session, $semester_id, $level_id);
            $outstanding_courses .= $student->outstanding_courses_list_string($session, $set, $semester_id, $level_id, 'C');
            foreach ($student->carry_overs($session, $semester_id, $level_id, true) as $carry_over) :
                $outstanding_courses .= sprintf('%s(CO), ', $carry_over->course_code);
            endforeach;
            foreach ($student->carry_forwards($session, $semester_id, $level_id, true) as $carry_forward) :
                $outstanding_courses .= sprintf('%s(CO), ', $carry_forward->course_code);
            endforeach;
            $student_arr['outstanding_courses'] = $outstanding_courses;

            //Previous
            $previous[] = number_format($student->get_courses_units($session, $semester_id - 1, $level_id, '', true), 2);
            $previous[] = number_format($student->get_result_points($session, $semester_id - 1, $level_id, true), 2);
            $previous[] = number_format($student->get_result_gp($session, $semester_id - 1, $level_id, true), 2);
            $student_arr['previous'] = $previous;

            //Present
            $present[] = number_format($student->get_courses_units($session, $semester_id, $level_id), 2);
            $present[] = number_format($student->get_result_points($session, $semester_id, $level_id), 2);
            $present[] = number_format($student->get_result_gp($session, $semester_id, $level_id), 2);
            $student_arr['present'] = $present;

            //Cumulative
            $cumulative[] = number_format($student->get_courses_units($session, $semester_id, $level_id, '', true), 2);
            $cumulative[] = number_format($student->get_result_points($session, $semester_id, $level_id, true), 2);
            $cumulative[] = number_format($student->get_result_gp($session, $semester_id, $level_id, true), 2);
            $student_arr['cumulative'] = $cumulative;

            //Remarks 
            $student_arr['remarks'] = $student->remarks($session, $set, $semester_id, $level_id, $graduating, $is_summary);

            //Registered Units
            $student_arr['units']['c'] = number_format($student->get_passed_courses_units($session, $semester_id, $level_id, 'C', true), 2);
            $student_arr['units']['e'] = number_format($student->get_passed_courses_units($session, $semester_id, $level_id, 'E', true), 2);
            $student_arr['units']['g'] = number_format($student->get_passed_courses_units($session, $semester_id, $level_id, 'G', true), 2);
        }
        return $student_arr;
    }

    static function addStudentRunningList(
        Student $student,
        $session,
        $semester_id,
        $level_id
    ) {
        return [
            'matric_number' => $student->matric_no,
            'name' => $student->full_name,
            'present_cp' => number_format($student->get_result_points($session, $semester_id, $level_id), 2),
            'present_tu' => number_format($student->get_courses_units($session, $semester_id, $level_id), 2),
            'present_gp' => number_format($student->get_result_gp($session, $semester_id, $level_id), 2),
            'cummulative_cp' => number_format($student->get_result_points($session, $semester_id, $level_id, true), 2),
            'cummulative_tu' => number_format($student->get_courses_units($session, $semester_id, $level_id, '', true), 2),
            'cummulative_gp' => number_format($student->get_result_gp($session, $semester_id, $level_id, true), 2),
        ];
    }
}
