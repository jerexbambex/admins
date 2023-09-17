<?php

namespace App\Http\Controllers;

use App\Imports\ScoreSheetImport;
use App\Models\LecturerCourse;
use App\Models\User;
use App\Services\ResultService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ResultController extends Controller
{
    public function uploadResult(Request $request)
    {
        $user = User::find($request->user()->id);

        $request->validate([
            'course_id' =>  'required',
            'session'   =>  'required',
            'file'      =>  'required|mimes:csv,xlsx,xls'
        ]);

        if (!$user || !$user->hasRole('lecturer')) {
            session()->flash('error_toast', 'Unable to upload, unauthorized access!');
            return redirect()->back();
        }

        $course_id = $request->course_id;
        $session = $request->session;

        $courseRef = LecturerCourse::find($course_id);

        if (!$courseRef || !$session) {
            session()->flash('error_toast', 'Unable to upload, no reference!');
            return redirect()->back();
        }

        Excel::import(new ScoreSheetImport($courseRef, $session), $request->file('file'));
        return redirect()->back();
    }

    public function print_scoresheet($encoded_session, $course_id, $lec_course_id)
    {
        return view(
            'exports.prints.scoresheet',
            compact('encoded_session', 'course_id', 'lec_course_id')
        );
    }

    public function print_semester_result(
        $set,
        $encoded_session,
        $semester_id,
        $prog_id,
        $level_id,
        $prog_type_id,
        $option_id,
        $bos_log_id = 0,
        ResultService $resultService
    ) {
        ini_set('max_execution_time', 300);

        extract($resultService->result_data(
            $set,
            $encoded_session,
            $semester_id,
            $prog_id,
            $level_id,
            $prog_type_id,
            $option_id,
            $bos_log_id
        ));

        $lists = [
            'pass_list', 'cso_list', 'warning_list', 'awaiting_result_list', 'withdrawal',
            'suspended_list', 'expelled_list', 'exam_malpractice_list', 'sick_list',
            'absent_list', 'dead_list'
        ];

        $pass_list = $warning_list = $awaiting_result_list =
            $withdrawal = $suspended_list = $expelled_list = $exam_malpractice_list =
            $sick_list = $absent_list = $cso_list = $dead_list = 0;

        $students_list = [];
        foreach ($students as $student) :
            if ($student->status == 'expelled') {
                $expelled_list++;
            } elseif ($student->status == 'withdrawn') {
                $withdrawal++;
            } elseif ($student->status == 'suspended') {
                $suspended_list++;
            } elseif ($student->status == 'dead') {
                $dead_list++;
            } else {
                // if ($student->deleted_at) {
                if ($student->hasPenalty('suspend', $session, $semester_id, $level_id)) {
                    $suspended_list++;
                } elseif ($student->hasPenalty('sick', $session, $semester_id, $level_id)) {
                    $sick_list++;
                } elseif ($student->hasPenalty('expel', $session, $semester_id, $level_id)) {
                    $expelled_list++;
                }
                // } 
                elseif (
                    // $student->hasPayment(explode('/', $session)[0], $semester_id, $level_id) &&
                    $student->hasCourseReg($session, $semester_id, $level_id) &&
                    $student->status == 'active'
                ) {
                    //Add Student
                    $data = $resultService->addStudentSemesterResult($student, $courses, $session, $set, $semester_id, $level_id, false, true);
                    $students_list[] = $data;

                    if ($student->hasEM($session, $semester_id, $level_id)) {
                        $exam_malpractice_list++;
                    } elseif ($student->isAbsent($session, $semester_id, $level_id)) {
                        $absent_list++;
                    } else {
                        $remark = strtolower($data['remarks']);
                        if ($remark == 'cso') {
                            $cso_list++;
                        } elseif ($remark == 'awr') {
                            $awaiting_result_list++;
                        } elseif ($remark == 'warning') {
                            $warning_list++;
                        } elseif ($remark == 'withdraw') {
                            $withdrawal_list++;
                        } elseif ($remark == 'pass') {
                            $pass_list++;
                        }
                    }
                }
            }
        endforeach;

        return view(
            'exports.prints.semester_result',
            compact(
                [
                    'semester_id',
                    'level_id',
                    'prog_type_id',
                    'department',
                    'faculty',
                    'option',
                    'session',
                    'level',
                    'students_list',
                    'set',
                    'courses',
                    'total_results_presented',
                    'lists',
                    'total_students',
                    'grand_total',
                    'bos_number',
                    'hasPresentation',
                    'presentation'
                ],
                $lists
            )
        );
    }

    public function print_running_list(
        $set,
        $encoded_session,
        $semester_id,
        $prog_id,
        $level_id,
        $prog_type_id,
        $option_id,
        $bos_log_id = 0,
        ResultService $resultService
    ) {
        ini_set('max_execution_time', 300);
        extract($resultService->result_data(
            $set,
            $encoded_session,
            $semester_id,
            $prog_id,
            $level_id,
            $prog_type_id,
            $option_id,
            $bos_log_id
        ));

        $lists = [
            'pass_list', 'cso_list', 'warning_list', 'carry_over_list',
            'carry_forward_list', 'withdrawal', 'awaiting_result_list',
            'suspended_list', 'expelled_list', 'exam_malpractice_list', 'sick_list',
            'absent_list', 'dead_list'
        ];
        if ($semester_id == 1) {
            unset($lists[3]);
            unset($lists[4]);
        } else unset($lists[1]);
        $lists = array_values($lists);

        $pass_list = $warning_list = $carry_over_list = $carry_forward_list = $awaiting_result_list =
            $withdrawal = $suspended_list = $expelled_list = $exam_malpractice_list =
            $sick_list = $absent_list = $cso_list = $dead_list = [];


        foreach ($students as $student) :
            if ($student->status == 'expelled') {
                $expelled_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } elseif ($student->status == 'withdrawn') {
                $withdrawal[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } elseif ($student->status == 'suspended') {
                $suspended_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } elseif ($student->status == 'dead') {
                $dead_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } else {
                // if ($student->deleted_at) {
                if ($student->hasPenalty('suspend', $session, $semester_id, $level_id)) {
                    $suspended_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                } elseif ($student->hasPenalty('sick', $session, $semester_id, $level_id)) {
                    $sick_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                } elseif ($student->hasPenalty('expel', $session, $semester_id, $level_id)) {
                    $expelled_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                } elseif ($student->hasPenalty('absent', $session, $semester_id, $level_id)) {
                    $absent_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                }
                // } 
                elseif (
                    // $student->hasPayment(explode('/', $session)[0], $semester_id, $level_id) &&
                    $student->hasCourseReg($session, $semester_id, $level_id) &&
                    $student->status == 'active'
                ) {
                    if ($student->hasEM($session, $semester_id, $level_id)) {
                        $exam_malpractice_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                    } elseif ($student->isAbsent($session, $semester_id, $level_id)) {
                        $absent_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                    } else {
                        $remark = strtolower($student->remarks($session, $set, $semester_id, $level_id));
                        if ($remark == 'cso') {
                            $cso_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'awr') {
                            $awaiting_result_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'co') {
                            $carry_over_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'cf') {
                            $carry_forward_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'warning') {
                            $warning_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'withdraw') {
                            $withdrawal[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'pass') {
                            $pass_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        }
                    }
                }
            }
        endforeach;

        return view(
            'exports.prints.running_list',
            compact(
                [
                    'semester_id',
                    'level_id',
                    'prog_type_id',
                    'department',
                    'faculty',
                    'option',
                    'session',
                    'level',
                    'set',
                    'courses',
                    'total_results_presented',
                    'lists',
                    'total_students',
                    'grand_total',
                    'bos_number',
                    'hasPresentation',
                    'presentation'
                ],
                $lists
            )
        );
    }

    public function print_semester_result_vetter(
        $set,
        $encoded_session,
        $semester_id,
        $prog_id,
        $level_id,
        $prog_type_id,
        $option_id,
        $bos_log_id = 0,
        ResultService $resultService
    ) {
        ini_set('max_execution_time', 300);
        extract($resultService->result_data(
            $set,
            $encoded_session,
            $semester_id,
            $prog_id,
            $level_id,
            $prog_type_id,
            $option_id,
            $bos_log_id
        ));

        $lists = [
            'pass_list', 'cso_list', 'warning_list', 'awaiting_result_list', 'withdrawal',
            'suspended_list', 'expelled_list', 'exam_malpractice_list', 'sick_list',
            'absent_list', 'dead_list'
        ];

        $pass_list = $warning_list = $awaiting_result_list =
            $withdrawal = $suspended_list = $expelled_list = $exam_malpractice_list =
            $sick_list = $absent_list = $cso_list = $dead_list = 0;

        $students_list = [];
        foreach ($students as $student) :
            if ($student->status == 'expelled') {
                $expelled_list++;
            } elseif ($student->status == 'withdrawn') {
                $withdrawal++;
            } elseif ($student->status == 'suspended') {
                $suspended_list++;
            } elseif ($student->status == 'dead') {
                $dead_list++;
            } else {
                // if ($student->deleted_at) {
                if ($student->hasPenalty('suspend', $session, $semester_id, $level_id)) {
                    $suspended_list++;
                } elseif ($student->hasPenalty('sick', $session, $semester_id, $level_id)) {
                    $sick_list++;
                } elseif ($student->hasPenalty('expel', $session, $semester_id, $level_id)) {
                    $expelled_list++;
                }
                // } 
                elseif (
                    // $student->hasPayment(explode('/', $session)[0], $semester_id, $level_id) &&
                    $student->hasCourseReg($session, $semester_id, $level_id) &&
                    $student->status == 'active' &&
                    !$student->hasAwaiting($session, $semester_id, $level_id)
                ) {
                    //Add Student
                    $data = $resultService->addStudentSemesterResult($student, $courses, $session, $set, $semester_id, $level_id, false, true);
                    $students_list[] = $data;

                    if ($student->hasEM($session, $semester_id, $level_id)) {
                        $exam_malpractice_list++;
                    } elseif ($student->isAbsent($session, $semester_id, $level_id)) {
                        $absent_list++;
                    } else {
                        $remark = strtolower($data['remarks']);
                        if ($remark == 'cso') {
                            $cso_list++;
                        } elseif ($remark == 'awr') {
                            $awaiting_result_list++;
                        } elseif ($remark == 'warning') {
                            $warning_list++;
                        } elseif ($remark == 'withdraw') {
                            $withdrawal_list++;
                        } elseif ($remark == 'pass') {
                            $pass_list++;
                        }
                    }
                }
            }
        endforeach;

        return view(
            'exports.prints.semester_result',
            compact(
                [
                    'semester_id',
                    'level_id',
                    'prog_type_id',
                    'department',
                    'faculty',
                    'option',
                    'session',
                    'level',
                    'students_list',
                    'set',
                    'courses',
                    'total_results_presented',
                    'lists',
                    'total_students',
                    'grand_total',
                    'bos_number',
                    'hasPresentation',
                    'presentation'
                ],
                $lists
            )
        );
    }

    public function print_running_list_vetter(
        $set,
        $encoded_session,
        $semester_id,
        $prog_id,
        $level_id,
        $prog_type_id,
        $option_id,
        $bos_log_id = 0,
        ResultService $resultService
    ) {
        ini_set('max_execution_time', 300);
        extract($resultService->result_data(
            $set,
            $encoded_session,
            $semester_id,
            $prog_id,
            $level_id,
            $prog_type_id,
            $option_id,
            $bos_log_id
        ));

        $lists = [
            'pass_list', 'cso_list', 'warning_list', 'carry_over_list',
            'carry_forward_list', 'withdrawal', 'awaiting_result_list',
            'suspended_list', 'expelled_list', 'exam_malpractice_list', 'sick_list',
            'absent_list', 'dead_list'
        ];
        if ($semester_id == 1) {
            unset($lists[3]);
            unset($lists[4]);
        } else unset($lists[1]);
        $lists = array_values($lists);

        $pass_list = $warning_list = $carry_over_list = $carry_forward_list = $awaiting_result_list =
            $withdrawal = $suspended_list = $expelled_list = $exam_malpractice_list =
            $sick_list = $absent_list = $cso_list = $dead_list = [];


        foreach ($students as $student) :
            if ($student->status == 'expelled') {
                $expelled_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } elseif ($student->status == 'withdrawn') {
                $withdrawal[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } elseif ($student->status == 'suspended') {
                $suspended_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } elseif ($student->status == 'dead') {
                $dead_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } else {
                // if ($student->deleted_at) {
                if ($student->hasPenalty('suspend', $session, $semester_id, $level_id)) {
                    $suspended_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                } elseif ($student->hasPenalty('sick', $session, $semester_id, $level_id)) {
                    $sick_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                } elseif ($student->hasPenalty('expel', $session, $semester_id, $level_id)) {
                    $expelled_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                } elseif ($student->hasPenalty('absent', $session, $semester_id, $level_id)) {
                    $absent_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                }
                // } 
                elseif (
                    // $student->hasPayment(explode('/', $session)[0], $semester_id, $level_id) &&
                    $student->hasCourseReg($session, $semester_id, $level_id) &&
                    $student->status == 'active' &&
                    !$student->hasAwaiting($session, $semester_id, $level_id)
                ) {
                    if ($student->hasEM($session, $semester_id, $level_id)) {
                        $exam_malpractice_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                    } elseif ($student->isAbsent($session, $semester_id, $level_id)) {
                        $absent_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                    } else {
                        $remark = strtolower($student->remarks($session, $set, $semester_id, $level_id));
                        if ($remark == 'cf') {
                            $carry_forward_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'awr') {
                            $awaiting_result_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'co') {
                            $carry_over_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'cso') {
                            $cso_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'warning') {
                            $warning_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'withdraw') {
                            $withdrawal[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'pass') {
                            $pass_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        }
                    }
                }
            }
        endforeach;

        return view(
            'exports.prints.running_list',
            compact(
                [
                    'semester_id',
                    'level_id',
                    'prog_type_id',
                    'department',
                    'faculty',
                    'option',
                    'session',
                    'level',
                    'set',
                    'courses',
                    'total_results_presented',
                    'lists',
                    'total_students',
                    'grand_total',
                    'bos_number',
                    'hasPresentation',
                    'presentation'
                ],
                $lists
            )
        );
    }

    public function print_semester_result_bos(
        $set,
        $encoded_session,
        $semester_id,
        $prog_id,
        $level_id,
        $prog_type_id,
        $option_id,
        $bos_log_id = 0,
        ResultService $resultService
    ) {
        ini_set('max_execution_time', 300);
        extract($resultService->result_data(
            $set,
            $encoded_session,
            $semester_id,
            $prog_id,
            $level_id,
            $prog_type_id,
            $option_id,
            $bos_log_id
        ));

        $lists = [
            'pass_list', 'cso_list', 'warning_list', 'awaiting_result_list', 'withdrawal',
            'suspended_list', 'expelled_list', 'exam_malpractice_list', 'sick_list',
            'absent_list', 'dead_list'
        ];

        $pass_list = $warning_list = $awaiting_result_list =
            $withdrawal = $suspended_list = $expelled_list = $exam_malpractice_list =
            $sick_list = $absent_list = $cso_list = $dead_list = 0;

        $students_list = [];
        foreach ($students as $student) :
            if ($student->status == 'expelled') {
                $expelled_list++;
            } elseif ($student->status == 'withdrawn') {
                $withdrawal++;
            } elseif ($student->status == 'suspended') {
                $suspended_list++;
            } elseif ($student->status == 'dead') {
                $dead_list++;
            } else {
                // if ($student->deleted_at) {
                if ($student->hasPenalty('suspend', $session, $semester_id, $level_id)) {
                    $suspended_list++;
                } elseif ($student->hasPenalty('sick', $session, $semester_id, $level_id)) {
                    $sick_list++;
                } elseif ($student->hasPenalty('expel', $session, $semester_id, $level_id)) {
                    $expelled_list++;
                }
                // } 
                elseif (
                    // $student->hasPayment(explode('/', $session)[0], $semester_id, $level_id) &&
                    $student->hasCourseReg($session, $semester_id, $level_id) &&
                    $student->status == 'active' &&
                    !$student->hasAwaiting($session, $semester_id, $level_id)
                ) {
                    //Add Student
                    $data = $resultService->addStudentSemesterResult($student, $courses, $session, $set, $semester_id, $level_id, false, true);
                    $students_list[] = $data;

                    if ($student->hasEM($session, $semester_id, $level_id)) {
                        $exam_malpractice_list++;
                    } elseif ($student->isAbsent($session, $semester_id, $level_id)) {
                        $absent_list++;
                    } else {
                        $remark = strtolower($data['remarks']);
                        if ($remark == 'cso') {
                            $cso_list++;
                        } elseif ($remark == 'awr') {
                            $awaiting_result_list++;
                        } elseif ($remark == 'warning') {
                            $warning_list++;
                        } elseif ($remark == 'withdraw') {
                            $withdrawal_list++;
                        } elseif ($remark == 'pass') {
                            $pass_list++;
                        }
                    }
                }
            }
        endforeach;

        return view(
            'exports.prints.semester_result',
            compact(
                [
                    'semester_id',
                    'level_id',
                    'prog_type_id',
                    'department',
                    'faculty',
                    'option',
                    'session',
                    'level',
                    'students_list',
                    'set',
                    'courses',
                    'total_results_presented',
                    'lists',
                    'total_students',
                    'grand_total',
                    'bos_number',
                    'hasPresentation',
                    'presentation'
                ],
                $lists
            )
        );
    }

    public function print_running_list_bos(
        $set,
        $encoded_session,
        $semester_id,
        $prog_id,
        $level_id,
        $prog_type_id,
        $option_id,
        $bos_log_id = 0,
        ResultService $resultService
    ) {
        ini_set('max_execution_time', 300);
        extract($resultService->result_data(
            $set,
            $encoded_session,
            $semester_id,
            $prog_id,
            $level_id,
            $prog_type_id,
            $option_id,
            $bos_log_id
        ));

        $lists = [
            'pass_list', 'cso_list', 'warning_list', 'carry_over_list',
            'carry_forward_list', 'withdrawal', 'awaiting_result_list',
            'suspended_list', 'expelled_list', 'exam_malpractice_list', 'sick_list',
            'absent_list', 'dead_list'
        ];
        if ($semester_id == 1) {
            unset($lists[3]);
            unset($lists[4]);
        } else unset($lists[1]);
        $lists = array_values($lists);

        $pass_list = $warning_list = $carry_over_list = $carry_forward_list = $awaiting_result_list =
            $withdrawal = $suspended_list = $expelled_list = $exam_malpractice_list =
            $sick_list = $absent_list = $cso_list = $dead_list = [];


        foreach ($students as $student) :
            if ($student->status == 'expelled') {
                $expelled_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } elseif ($student->status == 'withdrawn') {
                $withdrawal[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } elseif ($student->status == 'suspended') {
                $suspended_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } elseif ($student->status == 'dead') {
                $dead_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } else {
                // if ($student->deleted_at) {
                if ($student->hasPenalty('suspend', $session, $semester_id, $level_id)) {
                    $suspended_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                } elseif ($student->hasPenalty('sick', $session, $semester_id, $level_id)) {
                    $sick_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                } elseif ($student->hasPenalty('expel', $session, $semester_id, $level_id)) {
                    $expelled_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                } elseif ($student->hasPenalty('absent', $session, $semester_id, $level_id)) {
                    $absent_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                }
                // } 
                elseif (
                    // $student->hasPayment(explode('/', $session)[0], $semester_id, $level_id) &&
                    $student->hasCourseReg($session, $semester_id, $level_id) &&
                    $student->status == 'active' &&
                    !$student->hasAwaiting($session, $semester_id, $level_id)
                ) {
                    if ($student->hasEM($session, $semester_id, $level_id)) {
                        $exam_malpractice_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                    } elseif ($student->isAbsent($session, $semester_id, $level_id)) {
                        $absent_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                    } else {
                        $remark = strtolower($student->remarks($session, $set, $semester_id, $level_id));
                        if ($remark == 'cf') {
                            $carry_forward_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'awr') {
                            $awaiting_result_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'co') {
                            $carry_over_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'cso') {
                            $cso_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'warning') {
                            $warning_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'withdraw') {
                            $withdrawal[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'pass') {
                            $pass_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        }
                    }
                }
            }
        endforeach;

        return view(
            'exports.prints.running_list',
            compact(
                [
                    'semester_id',
                    'level_id',
                    'prog_type_id',
                    'department',
                    'faculty',
                    'option',
                    'session',
                    'level',
                    'set',
                    'courses',
                    'total_results_presented',
                    'lists',
                    'total_students',
                    'grand_total',
                    'bos_number',
                    'hasPresentation',
                    'presentation'
                ],
                $lists
            )
        );
    }

    public function print_graduating_semester_result(
        $set,
        $encoded_session,
        $semester_id,
        $prog_id,
        $level_id,
        $prog_type_id,
        $option_id,
        $bos_log_id = 0,
        ResultService $resultService
    ) {
        ini_set('max_execution_time', 300);
        extract($resultService->result_data(
            $set,
            $encoded_session,
            $semester_id,
            $prog_id,
            $level_id,
            $prog_type_id,
            $option_id,
            $bos_log_id
        ));

        $lists = [
            'distinction_list', 'upper_credit_list', 'lower_credit_list', 'pass_list', 'fail_list',
            're_run_list', 'withdrawal', 'awaiting_result_list',
            'suspended_list', 'expelled_list', 'exam_malpractice_list',
            'absent_list', 'sick_list', 'dead_list'
        ];

        $distinction_list = $upper_credit_list = $lower_credit_list = $pass_list = $fail_list =
            $withdrawal = $suspended_list = $re_run_list = $awaiting_result_list =
            $expelled_list = $exam_malpractice_list = $sick_list = $absent_list = $dead_list = 0;

        $students_list = [];
        foreach ($students as $student) :
            if ($student->status == 'expelled') {
                $expelled_list++;
            } elseif ($student->status == 'withdrawn') {
                $withdrawal++;
            } elseif ($student->status == 'suspended') {
                $suspended_list++;
            } elseif ($student->status == 'dead') {
                $dead_list++;
            } else {
                // if ($student->deleted_at) {
                if ($student->hasPenalty('suspend', $session, $semester_id, $level_id)) {
                    $suspended_list++;
                } elseif ($student->hasPenalty('sick', $session, $semester_id, $level_id)) {
                    $sick_list++;
                } elseif ($student->hasPenalty('expel', $session, $semester_id, $level_id)) {
                    $expelled_list++;
                }
                // } 
                elseif (
                    // $student->hasPayment(explode('/', $session)[0], $semester_id, $level_id) &&
                    $student->hasCourseReg($session, $semester_id, $level_id) &&
                    $student->status == 'active' &&
                    $student->canGraduate($session, $semester_id, $level_id) &&
                    !$student->hasAwaiting($session, $semester_id, $level_id)
                ) {
                    //Add Student
                    $data = $resultService->addStudentSemesterResult($student, $courses, $session, $set, $semester_id, $level_id, true, true);
                    $students_list[] = $data;

                    if ($student->hasEM($session, $semester_id, $level_id)) {
                        $exam_malpractice_list++;
                    } elseif ($student->isAbsent($session, $semester_id, $level_id)) {
                        $absent_list++;
                    } else {
                        $remark = strtolower($data['remarks']);
                        if ($remark == 'cf') {
                            $re_run_list++;
                        } elseif ($remark == 'awr') {
                            $awaiting_result_list++;
                        } elseif ($remark == 'co') {
                            $re_run_list++;
                        } elseif ($remark == 'cso') {
                            $re_run_list++;
                        } elseif ($remark == 'withdraw') {
                            $withdrawal_list++;
                        } elseif ($remark == 'fail') {
                            $fail_list++;
                        } elseif ($remark == 'pass') {
                            $pass_list++;
                        } elseif ($remark == 'lower credit') {
                            $lower_credit_list++;
                        } elseif ($remark == 'upper credit') {
                            $upper_credit_list++;
                        } elseif ($remark == 'distinction') {
                            $distinction_list++;
                        }
                    }
                }
            }
        endforeach;

        return view(
            'exports.prints.graduating_semester_result',
            compact(
                [
                    'semester_id',
                    'level_id',
                    'prog_type_id',
                    'department',
                    'faculty',
                    'option',
                    'session',
                    'level',
                    'students_list',
                    'set',
                    'courses',
                    'total_results_presented',
                    'lists',
                    'total_students',
                    'grand_total',
                    'bos_number',
                    'hasPresentation',
                    'presentation'
                ],
                $lists
            )
        );
    }

    public function print_graduating_running_list(
        $set,
        $encoded_session,
        $semester_id,
        $prog_id,
        $level_id,
        $prog_type_id,
        $option_id,
        $bos_log_id = 0,
        ResultService $resultService
    ) {

        extract($resultService->result_data(
            $set,
            $encoded_session,
            $semester_id,
            $prog_id,
            $level_id,
            $prog_type_id,
            $option_id,
            $bos_log_id
        ));

        $lists = [
            'distinction_list', 'upper_credit_list', 'lower_credit_list', 'pass_list', 'fail_list',
            're_run_list', 'withdrawal', 'awaiting_result_list',
            'suspended_list', 'expelled_list', 'exam_malpractice_list',
            'absent_list', 'sick_list', 'dead_list'
        ];

        $distinction_list = $upper_credit_list = $lower_credit_list = $pass_list = $fail_list =
            $withdrawal = $suspended_list = $re_run_list = $awaiting_result_list =
            $expelled_list = $exam_malpractice_list = $sick_list = $absent_list = $dead_list = [];

        foreach ($students as $student) :
            if ($student->status == 'expelled') {
                $expelled_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } elseif ($student->status == 'withdrawn') {
                $withdrawal[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } elseif ($student->status == 'suspended') {
                $suspended_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } elseif ($student->status == 'dead') {
                $dead_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
            } else {
                // if ($student->deleted_at) {
                if ($student->hasPenalty('suspend', $session, $semester_id, $level_id)) {
                    $suspended_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                } elseif ($student->hasPenalty('sick', $session, $semester_id, $level_id)) {
                    $sick_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                } elseif ($student->hasPenalty('expel', $session, $semester_id, $level_id)) {
                    $expelled_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                }
                // } 
                elseif (
                    // $student->hasPayment(explode('/', $session)[0], $semester_id, $level_id) &&
                    $student->hasCourseReg($session, $semester_id, $level_id) &&
                    $student->status == 'active' &&
                    $student->canGraduate($session, $semester_id, $level_id) &&
                    !$student->hasAwaiting($session, $semester_id, $level_id)
                ) {
                    if ($student->hasEM($session, $semester_id, $level_id)) {
                        $exam_malpractice_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                    } elseif ($student->isAbsent($session, $semester_id, $level_id)) {
                        $absent_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                    } else {
                        $remark = strtolower($student->remarks($session, $set, $semester_id, $level_id, true));
                        if ($remark == 'cf') {
                            $re_run_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'awr') {
                            $awaiting_result_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'co') {
                            $re_run_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'cso') {
                            $re_run_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'withdraw') {
                            $withdrawal[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'fail') {
                            $fail_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'pass') {
                            $pass_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'lower credit') {
                            $lower_credit_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'upper credit') {
                            $upper_credit_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        } elseif ($remark == 'distinction') {
                            $distinction_list[] = $resultService->addStudentRunningList($student, $session, $semester_id, $level_id);
                        }
                    }
                }
            }
        endforeach;

        return view(
            'exports.prints.graduating_running_list',
            compact(
                [
                    'semester_id',
                    'level_id',
                    'prog_type_id',
                    'department',
                    'faculty',
                    'option',
                    'session',
                    'level',
                    'set',
                    'courses',
                    'total_results_presented',
                    'lists',
                    'total_students',
                    'grand_total',
                    'bos_number',
                    'hasPresentation',
                    'presentation'
                ],
                $lists
            )
        );
    }
}
