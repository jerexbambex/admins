<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Level;
use App\Models\ProgrammeType;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\StudentResultsConfig;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ScoreSheetImport implements ToCollection
{
    public $session, $course, $code, $courseRef;
    public $for_sets = [], $for_cec = 0;

    public function __construct($courseRef, $session)
    {
        // if (!$courseRef) {
        //     return session()->flash('error_alert', "Unable to upload!");
        // }

        $this->courseRef = $courseRef;
        $this->course = $this->courseRef->course;
        $this->code = $this->course->thecourse_code;
        $this->for_sets = $this->course->for_set;
        $this->for_cec = $this->course->for_cec;
        $this->session = $session;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $c = 0;
        $session_year = explode('/', $this->session)[0];
        if (!$session_year) {
            return session()->flash('error_alert', "Session field cannot be empty!");
        }

        $semester = $this->course->semester;
        $semester_id = SEMESTER_KEYS[$semester];

        $config = StudentResultsConfig::whereSessionYear($session_year)->whereSemesterId($semester_id)->first([
            'lecturer_upload_start_date',
            'lecturer_upload_end_date'
        ]);

        if (!$config) {
            return session()->flash('error_alert', "Result upload for $this->session session, $semester is not yet enabled!");
        } elseif (!$config->lecturer_upload_start_date || !$config->lecturer_upload_end_date) {
            return session()->flash('error_alert', "Result upload for $this->session session, $semester is not yet enabled!");
        }

        $start = Carbon::parse($config->lecturer_upload_start_date);
        $end = Carbon::parse($config->lecturer_upload_end_date);
        $today = Carbon::now();

        if ($start->gte($end)) {
            return session()->flash('error_alert', "Result upload for $this->session session, $semester is not yet enabled!");
        }

        $upload_enabled = false;
        if ($today->gte($start) && $today->lte($end)) $upload_enabled = true;

        if (!$upload_enabled) {
            return session()->flash('error_alert', "Results Upload for $this->session session, $semester is closed!");
        }

        $dept_id = 0;
        $opt_id = 0;
        $level_id = 0;
        $prog_type_id = 0;
        $set = 0;

        //Validate filter parameters
        if (!isset($rows[0]) || !isset($rows[1]) || !isset($rows[2]) || !isset($rows[3]) || !isset($rows[4]) || !isset($rows[5]) || !isset($rows[6])) {
            return session()->flash('error_alert', 'Please re-download the scoresheet excel!');
        }
        $session = $rows[0][1];
        $course_code = $rows[1][1];
        $set = $rows[6][1];

        if (trim($session) != trim($this->session))
            return session()->flash('error_alert', 'Session mismatch!');

        if (!in_array($set, $this->for_sets))
            return session()->flash('error_alert', 'Set mismatch!');

        //Verify course code
        $codeParams = explode(' ', implode(' ', explode('/', $this->code)));
        $rowParams = explode(' ', implode(' ', explode('/', $course_code)));
        $mismatched = false;

        foreach ($rowParams as $param) //then
            if ($param && !in_array($param, $codeParams)) $mismatched = true;

        if ($mismatched) {
            return session()->flash('error_alert', "Course Code mismatch!");
        }

        $dept_name = isset($rows[2][1]) ? $rows[2][1] : '';
        $opt_name = isset($rows[3][1]) ? $rows[3][1] : '';
        $level_name = isset($rows[4][1]) ? $rows[4][1] : '';
        $prog_type_name = isset($rows[5][1]) ? $rows[5][1] : '';

        $level_name = str_replace('3', 'III', $level_name);
        $level_name = str_replace('2', 'II', $level_name);
        $level_name = str_replace('1', 'I', $level_name);

        $dept_data = Department::select(['departments_id'])->whereRaw("departments_name = '$dept_name'")->first();
        $level_data = Level::select('level_id')->whereRaw("level_name = '$level_name'")->first();
        $prog_type_data = ProgrammeType::select(['programmet_id'])->whereRaw("programmet_name = '$prog_type_name'")->first();

        // dd($dept_name, $opt_name, $level_name, $prog_type_name, $dept_data, $opt_data, $level_data, $prog_type_data);

        if (!$dept_data || !$level_data || !$prog_type_data) return session()->flash('error_alert', 'Parameter Mismatch!');
        $dept_id = $dept_data->departments_id;
        $level_id = $level_data->level_id;
        $prog_type_id = $prog_type_data->programmet_id;
        $prog_id = in_array($level_id, [1, 2]) ? 1 : 2;

        $opt_data = DeptOption::select(['do_id'])->whereProgId($prog_id)->whereRaw("programme_option = '$opt_name'")->first();
        if (!$opt_data) return session()->flash('error_alert', 'Parameter Mismatch!');
        $opt_id = $opt_data->do_id;


        if ($this->course->levels <> $level_id) {
            return session()->flash('error_alert', "Course level mismatch!");
        }

        foreach ($rows as $key => $row) {
            if ($key < 10) continue;
            if (!$row || !isset($row[1])) continue;

            // if ($c >= 7) {
            $c_a = $row[3];
            $mid_semester = $row[4];
            $examination = $row[5];
            $total = $row[6];
            $status = 'active';

            if (strtolower($total) === 'em' || strtolower($total) === 'abs' || strtolower($total) === 'absent') {
                $c_a = 0;
                $mid_semester = 0;
                $examination = 0;
                $total = 0;
                $status = strtolower($total);
            }

            $validated = is_numeric($c_a) && is_numeric($mid_semester) && is_numeric($examination) &&
                !is_null($c_a) && !is_null($mid_semester) && !is_null($examination);

            if (!$validated) continue;

            if ($validated) {
                $c_a = (float)$c_a;
                $mid_semester = (float)$mid_semester;
                $examination = (float)$examination;
                // if ($total != -2) 
                if ($c_a == 0 && $mid_semester == 0 and $examination == 0 and $total > 0) {
                    $examination = $total;
                } else $total = $c_a + $mid_semester + $examination;
                if ($total > 100) session()->flash('error_toast', 'Some results not uploaded due to miscalculation');
                if ($total <= 100) {
                    $total = (float)$total;
                    $matric_number = str_replace("'", '', $row[1]);
                    if ($matric_number) {
                        $student = Student::where(function ($query) use ($matric_number) {
                            $query->whereMatset($matric_number)->orWhere('matric_no', $matric_number);
                        })->whereStdAdmyear($set);
                        if ($this->for_cec) $student->where('stdprogrammetype_id', 2);
                        else $student->where('stdprogrammetype_id', '!=', 2);
                        $student = $student->first();
                        $exists = StudentResult::where(function ($query) use ($student, $matric_number) {
                            $query->whereMatricNumber($matric_number)
                                ->orWhere('log_id', $student->std_logid);
                        })
                            ->where('course_code', $this->code)
                            ->where('course_id', $this->course->thecourse_id)
                            ->where('session', $this->session)
                            ->count();

                        if ($student && !$exists) {
                            if ($student->stddepartment_id != $this->courseRef->lecturer->department_id || $dept_id != $this->courseRef->lecturer->department_id)
                                continue;
                            // dd("Check dept", $student->stddepartment_id, $this->courseRef->lecturer->department_id, $dept_id);
                            if ($student->stdprogrammetype_id != $this->courseRef->programme_type_id || $prog_type_id != $this->courseRef->programme_type_id)
                                continue;
                            // dd("Check prog type", $student->stdprogrammetype_id, $this->courseRef->programme_type_id, $prog_type_id);
                            if ($student->stdcourse != $this->course->stdcourse || $opt_id != $this->course->stdcourse)
                                continue;
                            // dd("Check course", $student->stdcourse, $this->course->stdcourse, $opt_id);
                            // if (($student->stdprogramme_id == 1 && !in_array($level_id, [1, 2])) || ($student->stdprogramme_id == 2 && !in_array($level_id, [3, 4])))
                            //     continue;
                            if ($student->std_admyear != $set)
                                continue;
                            // dd("Check set", $student->std_admyear, $set);

                            StudentResult::create([
                                'log_id'    =>  $student->std_logid,
                                'matric_number' =>  $matric_number,
                                'course_code'   =>  $this->code,
                                'semester'      =>  SEMESTER_KEYS[$this->course->semester],
                                'course_id'     =>  $this->course->thecourse_id,
                                'prog_type_id'     =>  $this->courseRef->programme_type_id,
                                'level_id'     =>  $this->course->levels,
                                'session'   =>  $this->session,
                                'c_a'       =>  $c_a,
                                'mid_semester'       =>  $mid_semester,
                                'examination'       =>  $examination,
                                'total'       =>  $total,
                                'grade'         =>  grade($total),
                                'lecturer_id'   =>  auth()->user()->id,
                                'lecturer_course_id'    =>  $this->courseRef->id,
                                'status'    =>  $status
                            ]);
                            $c++;
                        }
                    }
                }
            }
            // }
        }
        if ($c) {
            return session()->flash('success_alert', "$c Uploads successful!");
        }
        return session()->flash('error_toast', 'An error occured while uploading!');
    }
}
