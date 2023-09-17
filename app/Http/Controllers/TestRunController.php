<?php

namespace App\Http\Controllers;

use App\Exports\StudentsHistoryExport;
use App\Exports\StudentsStatisticsExport;
use App\Models\Applicant;
use App\Models\ChangeAppProgType;
use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Faculty;
use App\Models\LecturerCourse;
use App\Models\Programme;
use App\Models\Student;
use App\Models\StudentSession;
use App\Services\StudentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TestRunController extends Controller
{
    //
    public function student_session()
    {
        $students = Student::all();

        foreach ($students as $student) {
            $exists = StudentSession::whereFormNumber($student->matset)->orWhere('matric_number', $student->matric_no)->count();
            if (!$exists) {
                $form_number = $student->matset;
                $matric_number = $student->matric_no;
                if (!$student->matset) $form_number = $student->matric_no;

                $semesters = 2;
                if ($student->stdprogrammetype_id == 2) $semesters = 3;

                for ($semester = 1; $semester <= $semesters; $semester++) {
                    StudentSession::create([
                        'log_id' =>  $student->std_logid,
                        'session'       =>  sprintf('%s/%s', $student->std_admyear, (int) ($student->std_admyear) + 1),
                        'admission_year' =>  $student->std_admyear,
                        'semester'      =>  $semester,
                        'matric_number' =>  $matric_number,
                        'form_number'   =>  $form_number,
                        'level_id'      =>  $student->stdlevel,
                        'prog_id'       =>  $student->stdprogramme_id,
                        'prog_type_id'  =>  $student->stdprogrammetype_id
                    ]);
                }

                echo "$student->matset, $student->matric_no Done <br>";
            }
        }
    }


    public function update_student_status()
    {
        $students = StudentSession::where('course_form', 0)->orWhere('payment', 0)->get();

        foreach ($students as $student) {
            $data = [];

            //Course form update
            if ($student->student_data->hasCourseReg(explode('/', (string)$student->session)[0], $student->semester, $student->student_data->stdlevel)) $data['course_form'] = 1;

            //Payment data update
            if ($student->student_data->hasPayment(explode('/', (string)$student->session)[0], $student->semester, $student->student_data->stdlevel)) $data['payment'] = 1;

            $student->update($data);

            echo "$student->form_number for $student->semester Updated <br>";
        }
    }


    public function deleted_lec_courses()
    {
        $nonexists = [];
        $lec_courses = LecturerCourse::with('lecturer')->get();
        foreach ($lec_courses as $lec) if (!$lec->lecturer) $lec->delete();
        echo "Completed";
    }

    public function session_error_fix()
    {
        foreach (DB::table('student_sessions')->select(['form_number', 'log_id', 'session', 'level_id', 'admission_year'])
            ->groupBy(['form_number', 'log_id', 'session', 'level_id', 'admission_year'])
            ->havingRaw('COUNT(*) > 3')
            ->get() as $multiple) {
            if ($multiple) {
                StudentSession::whereLogId($multiple->log_id)->whereSession($multiple->session)
                    ->whereLevelId($multiple->level_id)->whereAdmissionYear($multiple->admission_year)
                    ->whereFormNumber($multiple->form_number)->delete();
                echo "$multiple->form_number Deleted";
            }
        }
    }

    public function no_account_std_logins_clear()
    {
        foreach (DB::table('stdlogin')->get(['log_id', 'log_username']) as $login) {
            $student = DB::table('stdprofile')->whereStdLogid($login->log_id)->count();
            if (!$student) {
                DB::table('stdlogin')->whereLogId($login->log_id)->delete();
                echo "Login $login->log_username Deleted";
            }
        }
    }

    public function students_wrong_matset_clear()
    {
        foreach (DB::table('stdprofile')->select(['std_id', 'matric_no', 'matset'])
            ->whereRaw("concat('', matric_no * 1) != matric_no")
            ->whereRaw("concat('', matset * 1) != matset")->get()
            as
            $student) {
            // dd($student);
            if (!is_numeric($student->matric_no) && !is_numeric($student->matset)) {
                DB::table('stdprofile')->update(['matset' => '0']);
            }
        }
        // dd($students);
        echo "Done";
    }


    public function matric_number_generator()
    {
        // dd(count(DB::table('stdprofile')
        // ->whereRaw("concat('', matric_no * 1) != matric_no")->skip(0)->limit(2)
        // ->get()));
        foreach (DB::table('stdprofile')
            ->whereRaw("concat('', matric_no * 1) != matric_no")->whereTouched(0)->take(3000)
            ->get()
            as
            $stddetail) {
            //Matric number generator
            //The pattern is YEAR-THECODE-SERIALNO
            $log_id = $stddetail->std_logid;

            //Department code
            $deptcode_query = DB::table('matcode')->select(['deptcode'])->where('do_id', $stddetail->stdcourse)
                ->where('progtype_id', $stddetail->stdprogrammetype_id)->where('prog_id', $stddetail->stdprogramme_id)->first();
            // dd($deptcode_query->deptcode, $stddetail);

            //Check for wrong dept parameters
            if (!$deptcode_query) {
                DB::table('stdprofile')->whereStdLogid($log_id)->update(['touched' => 2]);
                // continue;
            } else {
                // dd($deptcode_query, $stddetail->stdcourse, $stddetail->stdprogrammetype_id, $stddetail->stdprogramme_id);

                $deptcode = $deptcode_query->deptcode;

                $sess = $stddetail->std_admyear;

                //School Fee
                $fpay = DB::table('stdtransaction')->select(['trans_amount'])->where('log_id', $log_id)
                    ->where('trans_name', 'like', "%school%")->where('pay_status', "Paid")->where('trans_year', $sess)
                    ->where('trans_semester', 'like', '%first%')->count();

                if (!$fpay) {
                    DB::table('stdprofile')->whereStdLogid($log_id)->update(['touched' => 3]);
                    // continue;
                } else {
                    $form_no = !is_numeric($stddetail->matric_no) ? $stddetail->matric_no : $stddetail->matset;
                    $matric_no = StudentService::generateMatricNumber($stddetail->stdcourse, $sess, $deptcode);
                    //Matric number generator ends

                    $stdlogin = DB::table('stdlogin')->where('log_id', $log_id)->update([
                        'log_username'    =>    $matric_no,
                        'log_form_number'    =>    $form_no
                    ]);

                    $stdlogin = DB::table('stdprofile')->where('std_logid', $log_id)->update([
                        'matric_no'    =>    $matric_no,
                        'matset'    =>    $form_no,
                        'matric_confirmed' => 0
                    ]);

                    $std_session = DB::table('student_sessions')->where('log_id', $log_id)->where('session', session()->get('session'))->update([
                        'matric_number'    =>    $matric_no,
                        'form_number'    =>    $form_no
                    ]);

                    DB::table('stdprofile')->whereStdLogid($log_id)->update(['touched' => 1]);
                }
            }
        }
        echo "DONE";
    }

    public function set_other_false_payments_pending()
    {
        foreach (DB::table('stdtransaction')->where('pay_status', '!=', 'paid')->where('trans_name', 'like', '%school%')->get() as $transaction) {
            DB::table('stdtransaction')->whereTransNo($transaction->trans_no)->update(['pay_status' =>  $transaction->pay_status]);
        }
    }


    public function false_payments_phisher()
    {
        //Hostel - 2022
        // $transactions = DB::table('stdtransaction')->whereChannel('remita')->whereTransYear(2022)
        //     ->wherePayStatus('paid')->whereTransName('Hostel Accommodation')->get();

        //School - 2022
        // $transactions = DB::table('stdtransaction')->select(['trans_id', 'trans_no', 'rrr'])->whereTransYear(2022)
        //     ->wherePayStatus('Paid')->where('trans_name', 'like', '%school%')->whereVerifiedStatus(0)->orderBy('trans_id', 'desc')->take(600)->get();

        //School - 2020
        // $transactions = DB::table('stdtransaction')->select(['trans_id', 'trans_no', 'rrr'])->whereTransYear(2020)
        //     ->wherePayStatus('Paid')->where('trans_name', 'like', '%school%')->whereVerifiedStatus(0)
        //     ->whereBetween('trans_date', [Carbon::parse('2022-09-23 00:00:00'), Carbon::parse('2023-01-05 23:59:59')])
        //     ->orderBy('trans_id', 'desc')->take(600)->get();

        //Acceptance - 2022
        // $transactions = DB::table('stdtransaction')->select(['trans_id', 'trans_no', 'rrr'])->whereTransYear(2022)
        //     ->where('pay_status', 'Paid')->where('trans_name', 'like', '%acceptance%')->whereVerifiedStatus(0)->orderBy('trans_id', 'desc')->take(600)->get();

        //General Payments Phisher
        // ->whereBetween('trans_date', [Carbon::parse('2022-09-23 00:00:00'), Carbon::parse('2023-01-05 23:59:59')])
        // ->where('trans_name', 'like', '%school%')
        $transactions = DB::table('stdtransaction')->select(['trans_no', 'rrr'])
            ->wherePayStatus('Paid')
            ->whereVerifiedStatus(0)
            ->orderBy('trans_id', 'desc')->take(1000)->get();

        foreach ($transactions as $transaction) {
            $rrr = $transaction->rrr;
            $trans_no = $transaction->trans_no;

            if ($rrr) {
                $url     = "https://polyibadan.oystirev.com/api/rqr/trans?$rrr";
            } else {
                $mid = "PL01";
                $apkey = "1009";
                $hash_string = $trans_no . $apkey . $mid;
                $hash = hash('sha512', $hash_string);
                $url     = "http://polyibadan.oystirev.com/api/swi/trans?$trans_no/$hash";
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            $response = curl_exec($ch);
            $result = json_decode($response);
            // dd($result, base64_decode($result->Pay_items));

            if ($result) {
                if ($result->status != 'Paid') {
                    DB::table('stdtransaction')->whereTransNo($transaction->trans_no)->update([
                        'pay_status' => $result->status,
                        'verified_status' => 2,
                    ]);
                } else {
                    DB::table('stdtransaction')->whereTransNo($transaction->trans_no)->update([
                        'verified_status' => 1,
                        'trans_no'  =>  $result->ptrans_no,
                        'rrr'       =>  $result->rrr
                    ]);
                }
            } else {
                DB::table('stdtransaction')->whereTransNo($transaction->trans_no)->update([
                    'pay_status' => 'Pending',
                    'verified_status' => 3,
                ]);
            }
        }
    }

    public function get_hostel_payments_from_dom()
    {
        //used = [0 => 'not used', 1 => 'used', 2 => 'existed', 3 => 'touched']
        $acc_id = DB::table('ofield')->whereRaw("ofield_name like '%Accommodation%'")->first()->ofid;
        $ref_id = DB::table('ofield')->whereRaw("ofield_name like '%Refusal%'")->first()->ofid;

        foreach (DB::table('hostel_payments_dom')->whereUsed(0)->take(1000)->get() as $data) {
            $student = null;
            if (DB::table('stdtransaction')->whereTransNo($data->trans_id)->count() > 0) {
                DB::table('hostel_payments_dom')->whereTransId($data->trans_id)->update(['used' => 2]);
                continue;
            }
            if (!($student = DB::table('stdprofile')->whereMatset($data->formno)->orWhere('matric_no', $data->formno)->first())) {
                DB::table('hostel_payments_dom')->whereTransId($data->trans_id)->update(['used' => 3]);
                continue;
            }
            if ($student) {
                $amount = $data->amount;
                $trans_name = $amount == 5000 ? 'Hostel Refusal Fee' : 'Hostel Accommodation';
                $fee_id = $amount == 5000 ? $ref_id : $acc_id;
                $fee_data = [
                    'log_id' => $student->std_logid,
                    'trans_name' => $trans_name,
                    'trans_no' => $data->trans_id,
                    'rrr' => $data->ref,
                    'levelid' => $data->level,
                    'user_faculty' => $student->stdfaculty_id,
                    'user_dept' => $student->stddepartment_id,
                    'trans_amount' => $amount,
                    'generated_date' => date('Y-m-d H:i:s'),
                    'trans_date' => date('Y-m-d H:i:s'),
                    't_date' => date('Y-m-d'),
                    'trans_year' => explode('/', $data->session)[0],
                    'trans_semester' => SEMESTERS[$data->semester],
                    'pay_status' => "Paid",
                    'policy' => $data->pay_type,
                    'fullnames' => $data->names,
                    'prog_id' => $data->prog,
                    'prog_type' => $data->adm_type,
                    'stdcourse' => $student->stdcourse,
                    'appno' => $data->formno,
                    'appsor' => $data->state,
                    'channel' => 'Interswitch',
                    'fee_id' => $fee_id,
                    'fee_type' => 'ofees',
                    'lost_pay_ref' =>   "DOM"
                ];
                DB::table('hostel_payments_dom')->whereTransId($data->trans_id)->update(['used' => 1]);
                DB::table('stdtransaction')->insert($fee_data);
                echo "$data->formno DONE <br>";
            }
        }
    }


    public function downloadRegisteredStudentsByFaculty(
        $fac_id,
        $level_id,
        $sess_id,
        $prog_id,
        $progtype_id,
        $semester_id,
        $dept_id = 0
    ) {
        if ($fac_id) {
            if (!$dept_id)
                $department = Department::select(['departments_id', 'departments_name'])->whereFacId($fac_id)->first();
            else $department = Department::select(['departments_id', 'departments_name'])->whereFacId($fac_id)->where('departments_id', '>', $dept_id)->first();

            if ($department) {
                $dept_id = $department->departments_id;

                if (Department::select(['departments_id', 'departments_name'])->whereFacId($fac_id)->where('departments_id', '>', $dept_id)->first()) {
                    session()->put('download_next', true);
                    $downloadParams = func_get_args();
                    array_pop($downloadParams);
                    $downloadParams[] = $dept_id;
                    session()->put('download_params', $downloadParams);
                } else {
                    session()->remove('download_next');
                    session()->remove('download_params');
                }

                return StudentService::downloadRegisteredStudents(
                    $fac_id,
                    $dept_id,
                    0,
                    $level_id ? $level_id : 0,
                    $sess_id ? $sess_id : 0,
                    $prog_id ? $prog_id : 0,
                    $progtype_id ? $progtype_id : 0,
                    $semester_id ? $semester_id : 0
                );
            } else return redirect()->route('director.dashboard');

            // foreach ($departments as $dept) {
            // }
        } else dd('No faculty selected!');
    }

    public function downloadStudentsTuitionByFaculty(
        $fac_id,
        $level_id,
        $sess_id,
        $prog_id,
        $progtype_id,
        $semester_id,
        $dept_id = 0
    ) {
        if ($fac_id) {
            if (!$dept_id)
                $department = Department::select(['departments_id', 'departments_name'])->whereFacId($fac_id)->first();
            else $department = Department::select(['departments_id', 'departments_name'])->whereFacId($fac_id)->where('departments_id', '>', $dept_id)->first();

            if ($department) {
                $dept_id = $department->departments_id;

                if (Department::select(['departments_id', 'departments_name'])->whereFacId($fac_id)->where('departments_id', '>', $dept_id)->first()) {
                    session()->put('download_next_tuition', true);
                    $downloadParams = func_get_args();
                    array_pop($downloadParams);
                    $downloadParams[] = $dept_id;
                    session()->put('download_params_tuition', $downloadParams);
                } else {
                    session()->remove('download_next_tuition');
                    session()->remove('download_params_tuition');
                }

                return StudentService::downloadStudentPaymentsList(
                    $fac_id,
                    $dept_id,
                    0,
                    $level_id ? $level_id : 0,
                    $sess_id ? $sess_id : 0,
                    $prog_id ? $prog_id : 0,
                    $progtype_id ? $progtype_id : 0,
                    $semester_id ? $semester_id : 0
                );
            } else return redirect()->route('director.dashboard');

            // foreach ($departments as $dept) {
            // }
        } else dd('No faculty selected!');
    }

    public function downloadStudentsStatistics(
        $statistics_type = 'payment',
        $fac_id = 0,
        $dept_id = 0,
        $prog_id = 0,
        $opt_id = 0,
        $sess_id = 2020,
        $semester_id = 0
    ) {
        $file_name = 'Tuition Payments Statistics';
        if ($statistics_type == 'registered') $file_name = 'Registered Students Statistics';

        if ($fac_id) {
            if ($fac = Faculty::find($fac_id)) $file_name .= " - $fac->faculties_name";
        }
        if ($dept_id) {
            if ($dept = Department::find($dept_id)) $file_name .= " - $dept->departments_name";
        }
        if ($opt_id) {
            if ($opt = DeptOption::find($opt_id)) $file_name .= " - $opt->programme_option";
        }
        if ($prog_id) {
            if ($prog = Programme::find($prog_id)) $file_name .= " - $prog->programme_name";
        }
        if ($sess_id) {
            $session = sprintf('%s-%s', $sess_id, (int)$sess_id + 1);
            $file_name .= " - $session";
        }
        if ($semester_id) $file_name .= " - " . SEMESTERS[$semester_id];
        $title = $file_name;
        $file_name .= '.xlsx';

        $stats = \App\Services\StudentService::StudentsStatistics(
            $statistics_type,
            $fac_id,
            $dept_id,
            $prog_id,
            $opt_id,
            $sess_id,
            $semester_id
        )->get();

        return Excel::download(new StudentsStatisticsExport($stats, $title), $file_name);
    }

    public function reset_short_matric_numbers()
    {
        $students = DB::table('stdprofile')->whereRaw("
            length(matric_no) <= 11 and concat('', matric_no * 1) = matric_no
        ")->get(['std_logid', 'matric_no', 'matset']);
        foreach ($students as $student) {
            $matric_no = $student->matric_no;
            $init = substr($matric_no, 0, 9);
            $rest = substr($matric_no, 9);
            $newrest = str_pad($rest, 4, "0", STR_PAD_LEFT);
            $new_matric_no = "$init$newrest";
            $form_number = $student->matset;


            if (!DB::table('stdprofile')->whereMatricNo($new_matric_no)->count()) {
                DB::table('stdprofile')->whereStdLogid($student->std_logid)->update(['matric_no' =>  $new_matric_no]);
                DB::table('stdlogin')->whereLogId($student->std_logid)->update(['log_username' =>  $new_matric_no]);
                DB::table('student_sessions')->whereLogId($student->std_logid)->update(['matric_number' =>  $new_matric_no]);
            } else {
                DB::table('stdprofile')->whereStdLogid($student->std_logid)->update(['matric_no' =>  $form_number, 'matset' => 0, 'matric_confirmed' => 0, 'touched' => 0]);
                DB::table('stdlogin')->whereLogId($student->std_logid)->update(['log_username' =>  $form_number, 'log_form_number' => $form_number]);
                DB::table('student_sessions')->whereLogId($student->std_logid)->update(['matric_number' =>  $form_number, 'form_number' => $form_number]);
            }
        }
        return "DONE";
    }


    public function auto_push_unadmitted_to_cec()
    {
        $applicants = Applicant::whereAdmStatus(0)->whereAdmYear(2022)->wherePushed(0)->take(600)->get(['std_id', 'std_programmetype', 'app_no']);
        foreach ($applicants as $applicant) {
            if (!ChangeAppProgType::where([
                'applicant_id'  =>  $applicant->std_id,
                'initial_prog_type' =>  $applicant->std_programmetype,
                'new_prog_type' =>  2,
                'initial_appno' =>  $applicant->app_no
            ])->count()) {
                ChangeAppProgType::create([
                    'applicant_id'  =>  $applicant->std_id,
                    'initial_prog_type' =>  $applicant->std_programmetype,
                    'new_prog_type' =>  2,
                    'initial_appno' =>  $applicant->app_no
                ]);
            }
            $applicant->pushed = 1;
            $applicant->save();
        }
        echo "DONE";
    }

    public function downloadStudentsDataReport(
        $fac_id = 0,
        $dept_id = 0,
        $opt_id = 0,
        $level_id = 0,
        $sess_id = 2020,
        $prog_id = 0,
        $progtype_id = 0,
        $param = '',
        $search_param = ''
    ) {
        $search_param = base64_decode($search_param);
        $students = StudentService::studentsQuery(
            $fac_id,
            $dept_id,
            $opt_id,
            $level_id,
            $sess_id,
            $prog_id,
            $progtype_id,
            $search_param
        )->get();

        return Excel::download(new StudentsHistoryExport($students, $level_id, $param, $sess_id), "$param-students-report.xlsx");
    }


    public function move_2020_admissions_to_portalaccess()
    {
        foreach (DB::table('admissions_list_2020')->whereStatus(0)->take(5000)->get() as $admission) {
            if (DB::table('portalaccess')->whereAppno($admission->form_no)->count() === 0) {
                DB::table('portalaccess')->insert([
                    'appno' =>  $admission->form_no,
                    'stdno' =>  '',
                    'fullname' =>  sprintf('%s %s', $admission->surname, $admission->othernames),
                    'gender' =>  $admission->gender === 'M' ? 'Male' : 'Female',
                    'dept_id' =>  $admission->dept,
                    'dcos' =>  $admission->dept_option,
                    'school' =>  $admission->faculty,
                    'state' =>  $admission->state,
                    'prog' =>  $admission->prog,
                    'progtype' =>  $admission->prog_type,
                    'level' =>  $admission->prog === 1 ? 1 : 3,
                    'stdtype' =>  'new',
                    'adm_year' =>  $admission->adm_year,
                    'status' =>  0,
                    'date_admitted' =>  Carbon::now(),
                ]);
                DB::table('admissions_list_2020')->whereId($admission->id)->update([
                    'status'    =>  1
                ]);
            } else {
                DB::table('admissions_list_2020')->whereId($admission->id)->update([
                    'status'    =>  2
                ]);
            }
        }
    }


    function student_name_correction()
    {
        $data = DB::table('portalaccess')->selectRaw("portalaccess.*")->join("stdprofile", function ($join) {
            $join->on("stdprofile.matset", "portalaccess.appno")
                ->orOn("stdprofile.matric_no", "portalaccess.appno");
        })->whereRaw("stdprofile.firstname = ','")->get();
        foreach ($data as $d) {
            $fullname = explode(" ", str_replace(',', '', $d->fullname));
            if (count($fullname) > 2) {
                $newarr = array_values(array_filter($fullname, function ($ar) {
                    return !is_null($ar) && $ar !== '';
                }));

                DB::table('stdprofile')->whereMatricNo($d->appno)->orWhere('matset', $d->appno)->update([
                    'surname'   =>  $newarr[0],
                    'firstname' =>  $newarr[1],
                    'othernames' => isset($newarr[2]) ? $newarr[2] : ''
                ]);
            }
        }
    }

    function getGradePoint($type)
    {
        switch ($type) {
            case 'A1':
                return 7;

            case 'B2':
                return 6;

            case 'B3':
                return 5;

            case 'C4':
                return 4;

            case 'C5':
                return 3;

            case 'C6':
                return 2;

            case 'D7':
                return 1;

            default:
                return 0;
        }
    }

    function applicants_points_update()
    {
        foreach (Applicant::wherePushed(0)->whereAdmYear(2023)->orderBy('std_id', 'asc')->take(2000)->get() as $applicant) {
            $this->update_applicant_data($applicant);
        }
    }

    function applicants_points_update_2()
    {
        foreach (Applicant::wherePushed(0)->whereAdmYear(2023)->orderBy('std_id', 'desc')->take(2000)->get() as $applicant) {
            $this->update_applicant_data($applicant);
        }
    }

    function update_applicant_data(Applicant $applicant)
    {
        // $applicant->jambs_point = $applicant->jambs()->sum('jscore');
        if ($jamb = $applicant->jambs()->first()) {
            $applicant->regno = $jamb->jambno;
        } else {
            $applicant->regno = $applicant->app_no;
        }
        // $olevels = [];
        // $olevels_point = 0;
        // $olevels_string = "";
        // foreach ($applicant->olevels as $olevel) {
        //     $olevels_string .= sprintf("%s(%s),", substr($olevel->subname, 0, 3), $olevel->grade);
        //     if (isset($olevels[$olevel->examno])) {
        //         $olevels[$olevel->examno]['count'] += 1;
        //         $olevels[$olevel->examno]['point'] += $this->getGradePoint($olevel->grade);
        //     } else {
        //         $olevels[$olevel->examno]['count'] = 1;
        //         $olevels[$olevel->examno]['point'] = $this->getGradePoint($olevel->grade);
        //     }
        // }
        // foreach ($olevels as $olevel) {
        //     $olevels_point = ($olevel['point'] * 35) / (($olevel['count'] * 7));
        // }
        // if (count($olevels) > 1) {
        //     $olevels_point /= count($olevels);
        // }

        // $applicant->olevels_point = $olevels_point;
        // $applicant->olevels_string = $olevels_string;

        if ($applicant->stdprogramme_id == 2) {
            if ($eduhistory = DB::table('eduhistory')->whereStdId($applicant->std_logid)->first()) {
                $applicant->nd_matric_no = $eduhistory->ndmatno;
            }
        }

        $applicant->pushed = '1';

        $applicant->save();
    }

    function delete_multiple_admission_record_asc()
    {
        // SELECT appno, count(*)FROM `portalaccess` group by appno having count(*) > 2;
        $data = DB::table('portalaccess')->select(['appno', 'stdno', 'pid'])->where('adm_year', '<', '2020')->whereTouched(0)->groupBy('appno')->havingRaw('count(*) > 1')->orderBy('pid')->take(1000)->get();
        foreach ($data as $d) {
            DB::table('portalaccess')->whereAppno($d->appno)->whereStdno($d->stdno)->where('pid', '!=', $d->pid)->delete();
            DB::table('portalaccess')->whereAppno($d->appno)->update(['touched' => 1]);
        }
    }

    function delete_multiple_admission_record_desc()
    {
        // SELECT appno, count(*)FROM `portalaccess` group by appno having count(*) > 2;
        $data = DB::table('portalaccess')->select(['appno', 'stdno', 'pid'])->where('adm_year', '<', '2020')->whereTouched(0)->groupBy('appno')->havingRaw('count(*) > 1')->orderByDesc('pid')->take(1000)->get();
        foreach ($data as $d) {
            DB::table('portalaccess')->whereAppno($d->appno)->whereStdno($d->stdno)->where('pid', '!=', $d->pid)->delete();
            DB::table('portalaccess')->whereAppno($d->appno)->update(['touched' => 1]);
        }
    }
}
