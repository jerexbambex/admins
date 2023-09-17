<?php

namespace App\Services;

use App\Exports\RegisteredStudentsList;
use App\Exports\StudentPaymentList;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StudentService
{
    static function generateMatricNumber($course, $session, $dept_code, $last_inserted = 0)
    {
        if ($last_inserted) $count = $last_inserted + 1;
        else $count = DB::table('stdprofile')->select('std_logid')->where('stdcourse', $course)
            ->where('std_admyear', $session)->where('matset', '!=', '0')->count();

        return self::matricNumberExists($course, $session, $dept_code, $count);
    }

    static function matricNumberExists($course, $session, $dept_code, $count)
    {
        $countTotal = str_pad($count, 4, "0", STR_PAD_LEFT);
        $matric_no = "$session$dept_code$countTotal";
        $matric_no_check = "$session$dept_code$count";

        if (DB::table('stdprofile')->where('matric_no', $matric_no)->orWhere('matric_no', $matric_no_check)->count()) //then 
            return self::generateMatricNumber($course, $session, $dept_code, $count);

        return $matric_no;
    }

    static function studentsQuery(
        $fac_id = 0,
        $dept_id = 0,
        $opt_id = 0,
        $level_id = 0,
        $sess_id = 2020,
        $prog_id = 0,
        $progtype_id = 0,
        $search_param = ''
    ) {
        $students = Student::select(['*']);
        if ($fac_id) $students->where('stdfaculty_id', $fac_id);
        if ($dept_id) $students->where('stddepartment_id', $dept_id);
        if ($level_id) $students->where('stdlevel', $level_id);
        if ($prog_id) $students->where('stdprogramme_id', $prog_id);
        if ($opt_id) $students->where('stdcourse', $opt_id);
        if ($sess_id) $students->where('std_admyear', $sess_id);
        if ($progtype_id) $students->where('stdprogrammetype_id', $progtype_id);
        if ($search_param) {
            $students->where(function ($query) use ($search_param) {
                $query->whereRaw("matric_no like '%$search_param%'")
                    ->orWhereRaw("matset like '%$search_param%'");
            });
        }
        return $students;
    }

    static function getStudents(
        $fac_id = 0,
        $dept_id = 0,
        $opt_id = 0,
        $level_id = 0,
        $sess_id = 2020,
        $prog_id = 0,
        $progtype_id = 0,
        $semester_id = 0
    ) {
        $students = DB::table('stdprofile')
            ->selectRaw("
                surname as surname, 
                concat(concat(firstname, ' '), othernames) as othernames, 
                faculties.faculties_name as faculty, 
                departments.departments_name as department, 
                matric_no as matric_number,
                matset as form_number,
                stdlevel.level_name as level,
                programme_type.programmet_name as programme_type,
                stdprofile.student_mobiletel as telephone,
                stdprofile.student_email as email,
                stdtransaction.trans_name as trans_name,
                stdtransaction.trans_amount as trans_amount
            ")
            ->join('stdtransaction', 'stdtransaction.log_id', 'stdprofile.std_logid')
            ->join('faculties', 'faculties.faculties_id', 'stdprofile.stdfaculty_id')
            ->join('departments', 'departments.departments_id', 'stdprofile.stddepartment_id')
            ->join('stdlevel', 'stdlevel.level_id', 'stdtransaction.levelid')
            ->join('programme_type', 'programme_type.programmet_id', 'stdprofile.stdprogrammetype_id')
            ->join('programme', 'programme.programme_id', 'stdprofile.stdprogramme_id')
            ->whereRaw("
                pay_status = 'Paid' and trans_name like '%school%' 
                and log_id != 0  
            ");

        if ($fac_id) $students->where('stdprofile.stdfaculty_id', $fac_id);
        if ($dept_id && $fac_id) $students->where('stdprofile.stddepartment_id', $dept_id);
        if ($opt_id && $dept_id) $students->where('stdprofile.stdcourse', $opt_id);
        if ($sess_id) $students->where('stdtransaction.trans_year', $sess_id);
        if ($prog_id) $students->where('programme.programme_id', $prog_id);
        if ($level_id && $prog_id) $students->where('stdtransaction.levelid', $level_id);
        if ($progtype_id) $students->where('programme_type.programmet_id', $progtype_id);
        if ($semester_id) $students->where('stdtransaction.trans_semester', SEMESTERS[$semester_id]);
        // if ($sess_id) $students->where('stdprofile.std_admyear', $sess_id);
        return $students;
    }

    static function registeredStudents(
        $fac_id = 0,
        $dept_id = 0,
        $opt_id = 0,
        $level_id = 0,
        $sess_id = 2020,
        $prog_id = 0,
        $progtype_id = 0,
        $semester_id = 0
    ) {
        $students = self::getStudents(
            $fac_id,
            $dept_id,
            $opt_id,
            $level_id,
            $sess_id,
            $prog_id,
            $progtype_id,
            $semester_id
        );
        $students->whereRaw("stdtransaction.log_id in 
        (
            SELECT course_reg.log_id from course_reg 
            where course_reg.log_id = stdtransaction.log_id and course_reg.cyearsession = stdtransaction.trans_year 
            and course_reg.csemester = '" . SEMESTERS[$semester_id ? $semester_id : 1] . "'
        )");
        $students->orderByRaw("departments.departments_id, stdlevel.level_id, programme_type.programmet_id");
        return $students;
    }

    static function studentPayments(
        $fac_id = 0,
        $dept_id = 0,
        $opt_id = 0,
        $level_id = 0,
        $sess_id = 2020,
        $prog_id = 0,
        $progtype_id = 0,
        $semester_id = 0
    ) {
        $students = self::getStudents(
            $fac_id,
            $dept_id,
            $opt_id,
            $level_id,
            $sess_id,
            $prog_id,
            $progtype_id,
            $semester_id
        );

        $students->orderByRaw("departments.departments_id, stdlevel.level_id, programme_type.programmet_id");
        return $students;
    }

    static function downloadRegisteredStudents(
        $fac_id = 0,
        $dept_id = 0,
        $opt_id = 0,
        $level_id = 0,
        $sess_id = 2020,
        $prog_id = 0,
        $progtype_id = 0,
        $semester_id = 0
    ) {
        $students = StudentService::registeredStudents(
            $fac_id,
            $dept_id,
            $opt_id,
            $level_id,
            $sess_id,
            $prog_id,
            $progtype_id,
            $semester_id
        )->get();

        // dd($students->toArray());
        $output_file_name = "Registered Students' List";
        if ($fac_id) {
            $fcode = Faculty::select(['fcode'])->find($fac_id)->fcode;
            $output_file_name = "$fcode - $output_file_name";
        }
        if ($dept_id) {
            $department_name = Department::select(['departments_name'])->find($dept_id)->departments_name;
            $output_file_name .= " - $department_name";
        }
        if ($level_id) $output_file_name .= " - " . LEVELS[$level_id];
        if ($sess_id) $output_file_name .= " - $sess_id";
        if ($progtype_id) $output_file_name .= " - " . PROG_TYPES[$progtype_id];
        if ($semester_id) $output_file_name .= " - " . SEMESTERS[$semester_id];
        $output_file_name .= " - " . date('Y-m-d H-i-s') . '.xlsx';

        return Excel::download(new RegisteredStudentsList($students->toArray()), $output_file_name);
    }

    static function downloadStudentPaymentsList(
        $fac_id = 0,
        $dept_id = 0,
        $opt_id = 0,
        $level_id = 0,
        $sess_id = 2020,
        $prog_id = 0,
        $progtype_id = 0,
        $semester_id = 0
    ) {
        $students = StudentService::studentPayments(
            $fac_id,
            $dept_id,
            $opt_id,
            $level_id,
            $sess_id,
            $prog_id,
            $progtype_id,
            $semester_id
        )->get();

        // dd($students->toArray());
        $output_file_name = "Students' Tuition Fees";
        if ($fac_id) {
            $fcode = Faculty::select(['fcode'])->find($fac_id)->fcode;
            $output_file_name = "$fcode - $output_file_name";
        }
        if ($dept_id) {
            $department_name = Department::select(['departments_name'])->find($dept_id)->departments_name;
            $output_file_name .= " - $department_name";
        }
        if ($level_id) $output_file_name .= " - " . LEVELS[$level_id];
        if ($sess_id) $output_file_name .= " - $sess_id";
        if ($progtype_id) $output_file_name .= " - " . PROG_TYPES[$progtype_id];
        if ($semester_id) $output_file_name .= " - " . SEMESTERS[$semester_id];
        $output_file_name .= " - " . date('Y-m-d H-i-s') . '.xlsx';

        return Excel::download(new StudentPaymentList($students->toArray()), $output_file_name);
    }

    static function StudentsStatistics(
        $type = 'registered',
        $fac_id = 0,
        $dept_id = 0,
        $prog_id = 0,
        $opt_id = 0,
        $sess_id = 2020,
        $semester_id = 0
    ) {
        $user = auth()->user();
        $progtype_id = $user->prog_type_id;

        $selectStatement = [];
        if ($fac_id) {
            $selectStatement[] = "faculties.faculties_name as faculty";
            if ($dept_id) {
                $selectStatement[] = "departments.departments_name as department";
                if ($opt_id) $selectStatement[] = "dept_options.programme_option as option_name";
            }
        }
        if ($prog_id) $selectStatement[] = "stdlevel.level_name as level_name";
        $selectStatement[] = "concat(stdtransaction.trans_year, concat('/', concat(stdtransaction.trans_year+1, ''))) as std_session";
        $selectStatement[] = "programme_type.programmet_name as programme_type";
        $selectStatement[] = "count(stdprofile.std_id) as total_count";

        $students = DB::table('stdprofile')
            ->selectRaw(implode(',', $selectStatement))
            ->join('stdtransaction', 'stdtransaction.log_id', 'stdprofile.std_logid')
            ->join('faculties', 'faculties.faculties_id', 'stdprofile.stdfaculty_id')
            ->join('departments', 'departments.departments_id', 'stdprofile.stddepartment_id')
            ->join('dept_options', 'dept_options.do_id', 'stdprofile.stdcourse')
            ->join('stdlevel', 'stdlevel.level_id', 'stdtransaction.levelid')
            ->join('programme_type', 'programme_type.programmet_id', 'stdprogrammetype_id')
            ->join('programme', 'programme.programme_id', 'stdprogramme_id')
            ->whereTransYear($sess_id)
            ->wherePayStatus('paid')
            ->where('trans_name', 'like', '%school%')
            ->where('log_id', '!=', '0');

        if ($progtype_id) $students->where('programme_type.programmet_id', $progtype_id);

        if ($type == 'registered') {
            $students->whereRaw("stdtransaction.log_id in 
            (
                SELECT course_reg.log_id from course_reg 
                where course_reg.log_id = stdtransaction.log_id and course_reg.cyearsession = stdtransaction.trans_year 
                and course_reg.csemester = '" . SEMESTERS[$semester_id ? $semester_id : 1] . "'
            )");
        }

        if ($fac_id) $students->where('faculties.faculties_id', $fac_id);
        if ($dept_id) $students->where('stdprofile.stddepartment_id', $dept_id);
        if ($opt_id) $students->where('stdprofile.stdcourse', $opt_id);
        if ($prog_id) $students->where('stdprofile.stdprogramme_id', $prog_id);
        if ($semester_id) $students->where('stdtransaction.trans_semester', SEMESTERS[$semester_id]);

        $groupByClause = ['stdtransaction.trans_year'];
        if ($fac_id) $groupByClause[] = "faculties.faculties_id";
        if ($dept_id) $groupByClause[] = "departments.departments_id";
        if ($opt_id) $groupByClause[] = "dept_options.do_id";
        if ($prog_id) $groupByClause[] = "stdlevel.level_id";
        $groupByClause[] = 'programme_type.programmet_id';
        $orderByClause = $groupByClause;
        unset($orderByClause[0]);

        $students->groupByRaw(implode(',', $groupByClause))
            ->orderByRaw(implode(',', $orderByClause));
        return $students;
    }

    static function StudentsStatisticsOld(
        $type = 'registered',
        $fac_id = 0,
        $dept_id = 0,
        $prog_id = 0,
        $opt_id = 0,
        $sess_id = 2020,
        $semester_id = 0
    ) {
        dd(func_get_args());
        //fac, dept, opt, level, prog_type
        $students = DB::table('stdprofile');
        if (!$fac_id)
            $students->selectRaw("
                faculties.faculties_name as faculty,
                stdlevel.level_name as level_name, 
                programme_type.programmet_name as programme_type, 
                count(stdprofile.std_id) as total_count
            ");
        elseif (!$dept_id)
            $students->selectRaw("
                faculties.faculties_name as faculty, 
                departments.departments_name as department,
                stdlevel.level_name as level_name, 
                programme_type.programmet_name as programme_type, 
                count(stdprofile.std_id) as total_count
            ");
        else
            $students->selectRaw("
                faculties.faculties_name as faculty, 
                departments.departments_name as department, 
                dept_options.programme_option as option_name,
                stdlevel.level_name as level_name, 
                programme_type.programmet_name as programme_type, 
                count(stdprofile.std_id) as total_count
            ");

        $students->join('stdtransaction', 'stdtransaction.log_id', 'stdprofile.std_logid')
            ->join('faculties', 'faculties.faculties_id', 'stdprofile.stdfaculty_id')
            ->join('departments', 'departments.departments_id', 'stdprofile.stddepartment_id')
            ->join('dept_options', 'departments.departments_id', 'dept_options.dept_id')
            ->join('stdlevel', 'stdlevel.level_id', 'stdtransaction.levelid')
            ->join('programme_type', 'programme_type.programmet_id', 'stdprofile.stdprogrammetype_id')
            ->join('programme', 'programme.programme_id', 'stdprofile.stdprogramme_id')
            ->whereRaw("
                pay_status = 'Paid' and trans_name like '%school%' 
                and log_id != 0  
            ");

        if ($type == 'registered')
            $students->whereRaw("stdtransaction.log_id in 
            (
                SELECT course_reg.log_id from course_reg 
                where course_reg.log_id = stdtransaction.log_id and course_reg.cyearsession = stdtransaction.trans_year 
                and course_reg.csemester = '" . SEMESTERS[$semester_id ? $semester_id : 1] . "'
            )");

        if ($fac_id) $students->where('stdprofile.stdfaculty_id', $fac_id);
        if ($dept_id && $fac_id) $students->where('stdprofile.stddepartment_id', $dept_id);
        if ($opt_id && $dept_id) $students->where('stdprofile.stdcourse', $opt_id);
        if ($sess_id) $students->where('stdtransaction.trans_year', $sess_id);
        if ($prog_id) $students->where('programme.programme_id', $prog_id);
        if ($semester_id) $students->where('stdtransaction.trans_semester', SEMESTERS[$semester_id]);
        // if ($sess_id) $students->where('stdprofile.std_admyear', $sess_id);

        //Group
        $groupBy = ['faculties.faculties_id'];
        if ($fac_id) $groupBy[] = 'departments.departments_id';
        if ($dept_id) $groupBy[] = 'dept_options.do_id';
        $groupBy[] = 'stdlevel.level_id';
        $groupBy[] = 'programme_type.programmet_id';
        $students->groupBy($groupBy)->orderByRaw(implode(',', $groupBy));

        return $students;
    }
}
