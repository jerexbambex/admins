<?php

namespace App\Services;

use App\Exports\ApplicantsExport;
use App\Models\Applicant;
use App\Models\Portal;
use App\Models\StdLogin;
use App\Models\Student;
use App\Models\StudentSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdmissionsService
{
    static function getApplicants(
        $session = null,
        $faculty_id = 0,
        $department_id = 0,
        $prog_id = 0,
        $prog_type_id = 0,
        $adm_status = 0,
        $search = ''
    ) {
        $applicants = Applicant::withSum('jambs', 'jscore')->with(['olevels']);
        if ($session) $applicants->where('adm_year', $session);
        if ($prog_id) $applicants->where('stdprogramme_id', $prog_id);
        if ($prog_type_id) $applicants->where('std_programmetype', $prog_type_id);
        if ($faculty_id) $applicants->where('fac_id', $faculty_id);
        if ($department_id) $applicants->where('dept_id', $department_id);

        if ($search) {
            $search_params = explode(' ', $search);
            $count_params = count($search_params);

            switch ($count_params) {
                case 3:
                    $applicants->where(function ($query) use ($search_params) {
                        $query->where('surname', 'like', '%' . $search_params[0] . '%')
                            ->where('firstname', 'like', '%' . $search_params[1] . '%')
                            ->where('othernames', 'like', '%' . $search_params[2] . '%');
                    });
                    break;

                case 2:
                    $applicants->where(function ($query) use ($search_params) {
                        $query->where('surname', 'like', '%' . $search_params[0] . '%')
                            ->where('firstname', 'like', '%' . $search_params[1] . '%')
                            ->orwhere('othernames', 'like', '%' . $search_params[1] . '%');
                    });
                    break;

                default:
                    $applicants->where(function ($query) use ($search_params) {
                        $query->where('app_no', 'like', '%' . $search_params[0] . '%')
                            ->orwhere('surname', 'like', '%' . $search_params[0] . '%')
                            ->orwhere('firstname', 'like', '%' . $search_params[0] . '%')
                            ->orwhere('othernames', 'like', '%' . $search_params[0] . '%');
                    });
                    break;
            }
        }

        if ($adm_status != 3) $applicants->where('adm_status', $adm_status);
        $applicants = $applicants->orderByRaw('jambs_point DESC, olevels_point DESC, adm_status ASC, std_custome9 DESC, adm_year ASC, fac_id ASC, dept_id ASC, stdprogramme_id ASC, stdcourse ASC, std_programmetype ASC');
        return $applicants;
    }

    static function downloadApplicants(
        $session = null,
        $faculty_id = 0,
        $department_id = 0,
        $prog_id = 0,
        $prog_type_id = 0,
        $adm_status = 0,
        $search = ''
    ) {
        $applicants = DB::table('application_profile')
            ->selectRaw("std_logid, app_no, concat(surname, concat(' ', concat(firstname, concat(' ', othernames)))) as full_name, departments.departments_name as department_name, dept_options.programme_option as course_name, programme.programme_name, programme_type.programmet_name as programme_type, student_mobiletel, student_email, std_custome9 as submit_status, adm_status, jambs_point, olevels_string, regno, nd_matric_no, stdprogramme_id as prog_id")
            ->join('departments', 'departments.departments_id', 'application_profile.dept_id')
            ->join('dept_options', 'dept_options.do_id', 'application_profile.stdcourse')
            ->join('programme', 'programme.programme_id', 'application_profile.stdprogramme_id')
            ->join('programme_type', 'programme_type.programmet_id', 'application_profile.std_programmetype');

        if ($session) $applicants->where('application_profile.adm_year', $session);
        if ($prog_id) $applicants->where('application_profile.stdprogramme_id', $prog_id);
        if ($prog_type_id) $applicants->where('application_profile.std_programmetype', $prog_type_id);
        if ($faculty_id) $applicants->where('application_profile.fac_id', $faculty_id);
        if ($department_id) $applicants->where('application_profile.dept_id', $department_id);

        if ($search) {
            $search_params = explode(' ', $search);
            $count_params = count($search_params);

            switch ($count_params) {
                case 3:
                    $applicants->where(function ($query) use ($search_params) {
                        $query->where('surname', 'like', '%' . $search_params[0] . '%')
                            ->where('firstname', 'like', '%' . $search_params[1] . '%')
                            ->where('othernames', 'like', '%' . $search_params[2] . '%');
                    });
                    break;

                case 2:
                    $applicants->where(function ($query) use ($search_params) {
                        $query->where('surname', 'like', '%' . $search_params[0] . '%')
                            ->where('firstname', 'like', '%' . $search_params[1] . '%')
                            ->orwhere('othernames', 'like', '%' . $search_params[1] . '%');
                    });
                    break;

                default:
                    $applicants->where(function ($query) use ($search_params) {
                        $query->where('app_no', 'like', '%' . $search_params[0] . '%')
                            ->orwhere('surname', 'like', '%' . $search_params[0] . '%')
                            ->orwhere('firstname', 'like', '%' . $search_params[0] . '%')
                            ->orwhere('othernames', 'like', '%' . $search_params[0] . '%');
                    });
                    break;
            }
        }

        if ($adm_status != 3) $applicants->where('application_profile.adm_status', $adm_status);
        return $applicants->orderByRaw('application_profile.jambs_point DESC, application_profile.olevels_point DESC, application_profile.adm_status ASC, application_profile.std_custome9 DESC, application_profile.adm_year ASC, application_profile.fac_id ASC, application_profile.dept_id ASC, application_profile.stdprogramme_id ASC, application_profile.stdcourse ASC, application_profile.std_programmetype ASC')->get();
    }

    static function getStudents(
        $adm_year = null,
        $prog_id = 0,
        $prog_type_id = 0,
        $do_id = 0,
        $department_id = 0,
        $fac_id = 0,
        $clear_status = 0,
        $search = '',
        $date_from = null,
        $date_to = null
    ) {

        $students = Student::whereStdAdmyear($adm_year);
        if ($prog_id) $students->where('stdprogramme_id', $prog_id);
        if ($prog_type_id) $students->where('stdprogrammetype_id', $prog_type_id);

        if ($do_id) $students->whereStdcourse($do_id);
        elseif ($department_id) $students->whereStddepartmentId($department_id);
        elseif ($fac_id) $students->whereStdfacultyId($fac_id);

        if ($clear_status <> "all") $students->whereEclearance($clear_status);

        if ($search) {
            $search_params = explode(' ', $search);
            $count_params = count($search_params);

            if ($count_params == 3)
                $students->where(function ($query) use ($search_params) {
                    $query->where('surname', 'like', '%' . $search_params[0] . '%')
                        ->where('firstname', 'like', '%' . $search_params[1] . '%')
                        ->where('othernames', 'like', '%' . $search_params[2] . '%');
                });

            elseif ($count_params == 2)
                $students->where(function ($query) use ($search_params) {
                    $query->where('surname', 'like', '%' . $search_params[0] . '%')
                        ->where('firstname', 'like', '%' . $search_params[1] . '%')
                        ->orwhere('othernames', 'like', '%' . $search_params[1] . '%');
                });

            else
                $students->where(function ($query) use ($search_params) {
                    $query->where('matric_no', 'like', '%' . $search_params[0] . '%')
                        ->orwhere('matset', 'like', '%' . $search_params[0] . '%')
                        ->orwhere('surname', 'like', '%' . $search_params[0] . '%')
                        ->orwhere('firstname', 'like', '%' . $search_params[0] . '%')
                        ->orwhere('othernames', 'like', '%' . $search_params[0] . '%');
                });
        }
        if ($clear_status && $date_from && $date_to) {
            $date_from = Carbon::parse($date_from);
            $date_to = Carbon::parse($date_to);
            $students->whereBetween('date_cleared', [$date_from, $date_to]);
        }
        $students->orderBy('stddepartment_id', 'asc');
        return $students;
    }

    public function admissionTemplateData($session_year, $prog_id, $prog_type_id, $search = '')
    {
        $search = base64_decode($search);
        $applicants = Applicant::where('adm_year', $session_year);
        if ($prog_id) $applicants->where('stdprogramme_id', $prog_id);
        $applicants->where('adm_status', 0);
        $applicants->where('std_custome9', 1);
        if (auth()->user()->prog_type_id) $applicants->where('std_programmetype', auth()->user()->prog_type_id);
        elseif ($prog_type_id) $applicants->where('std_programmetype', $prog_type_id);
        if ($search)
            $applicants->where('app_no', 'like', '%' . $search . '%');

        $applicants = $applicants;

        return $applicants;
    }

    // static function downloadApplicants(
    //     $session = null,
    //     $faculty_id = 0,
    //     $department_id = 0,
    //     $prog_id = 0,
    //     $prog_type_id = 0,
    //     $adm_status = 0,
    //     $search = '',
    //     $file_name = ''
    // ) {
    //     try {
    //         $applicants = self::getApplicants(
    //             $session,
    //             $faculty_id,
    //             $department_id,
    //             $prog_id,
    //             $prog_type_id,
    //             $adm_status,
    //             $search
    //         )->get();

    //         return Excel::download(new ApplicantsExport($applicants), $file_name ? $file_name : 'applicants.xlsx');
    //     } catch (\Throwable $th) {
    //         session()->flash('error_toast', "An error occured while processing download");
    //         return redirect()->back();
    //     }
    // }

    public static function admitStudent(Applicant $applicant)
    {
        // if ($applicant->app_no !== 'F230000F') {
        //     session()->flash('error_toast', 'This service is in development mode, please wait!');
        //     return false;
        // }
        try {
            if ($applicant->adm_status === 1) {
                session()->flash('error_toast', 'Applicant already admitted!');
                return false;
            }
            $data = [
                'appno' =>  $applicant->app_no,
                'stdno' =>  $applicant->nd_matric_no,
                'fullname' =>  $applicant->full_name,
                'gender' =>  $applicant->gender,
                'dept_id' =>  $applicant->dept_id,
                'dcos' =>  $applicant->stdcourse,
                'school' =>  $applicant->fac_id,
                'state' =>  $applicant->state_of_origin,
                'prog' =>  $applicant->stdprogramme_id,
                'progtype' =>  $applicant->std_programmetype,
                'level' =>  $applicant->stdprogramme_id == 1 ? 1 : 3,
                'adm_year' =>  $applicant->adm_year
            ];

            $applicant->adm_status = 1;
            $applicant->date_admitted = now();
            $admitted = Portal::whereAppno($data['appno'])->first();
            if (!$admitted) {
                $admitted = Portal::create($data);
            }
            if ($admitted && $applicant->save()) {
                return self::addStudentProfileData($applicant, $admitted);
            }
            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function addStudentProfileData(Applicant $applicant, Portal $admitted)
    {
        try {
            $applogin = $applicant->app_login->toArray();
            unset($applogin['log_gsm']);
            $applogin['log_form_number'] = $applogin['log_username'];
            $applogin['log_username'] = $admitted->stdno ? $admitted->stdno : $applicant->app_no;

            $stdlogin = StdLogin::where('log_username', $applicant->app_no)->orWhere('log_form_number', $applicant->app_no)->first();

            if (!$stdlogin) {
                $stdlogin = StdLogin::create($applogin);
            }

            if ($stdlogin) {
                $profileData = (array)DB::table('application_profile')->whereStdId($applicant->std_id)->first();

                $stdprofile = Student::where(function ($q) use ($applogin) {
                    $q->whereMatricNo($applogin['log_username'])->orWhere('matset', $applogin['log_username']);
                })->orWhere(function ($q) use ($applogin) {
                    $q->whereMatricNo($applogin['log_form_number'])->orWhere('matset', $applogin['log_form_number']);
                })->first();

                if (!$stdprofile) {
                    $profileData['std_logid'] = $stdlogin->log_id;
                    $profileData['stdprogrammetype_id'] = $profileData['std_programmetype'];
                    $profileData['stddepartment_id'] = $profileData['dept_id'];
                    $profileData['stdfaculty_id'] = $profileData['fac_id'];
                    $profileData['std_admyear'] = $profileData['adm_year'];
                    unset($profileData['std_id']);
                    unset($profileData['app_no']);
                    unset($profileData['regno']);
                    unset($profileData['std_programmetype']);
                    unset($profileData['dept_id']);
                    unset($profileData['fac_id']);
                    unset($profileData['adm_year']);
                    unset($profileData['biodata']);
                    unset($profileData['appsubmitdate']);
                    unset($profileData['std_custome5']);
                    unset($profileData['std_custome6']);
                    unset($profileData['std_custome7']);
                    unset($profileData['std_custome8']);
                    unset($profileData['std_custome9']);
                    unset($profileData['ndcert']);
                    unset($profileData['adm_status']);
                    unset($profileData['update_status']);
                    unset($profileData['exam_date']);
                    unset($profileData['examdate_resetable']);
                    unset($profileData['date_admitted']);
                    unset($profileData['last_inserted']);
                    unset($profileData['pushed']);
                    unset($profileData['jambs_point']);
                    unset($profileData['olevels_point']);
                    unset($profileData['olevels_string']);
                    unset($profileData['nd_matric_no']);

                    $extraData = [
                        'matric_no' =>  $applogin['log_username'],
                        'matset'    =>  $admitted->stdno ? $stdlogin->log_form_number : 0,
                        'stdlevel'  =>  $profileData['stdprogramme_id'] == 1 ? 1 : 3,
                        'std_status' => 'new'
                    ];

                    $stdprofile_data = array_merge($profileData, $extraData);

                    // dd($stdprofile_data);

                    $stdprofile = DB::table('stdprofile')->insert($stdprofile_data);

                    if ($stdprofile) {
                        $admitted->status = 1;
                        $admitted->save();

                        $semesters = 2;
                        if ($profileData['stdprogrammetype_id'] == 2) $semesters = 3;

                        foreach (range(1, $semesters) as $semester) {
                            $data = [
                                'log_id'    =>  $profileData['std_logid'],
                                'form_number'   =>  $applogin['log_form_number'],
                                'matric_number'   =>  $applogin['log_username'],
                                'session'    =>  sprintf('%s/%s', $profileData['std_admyear'], (int)$profileData['std_admyear'] + 1),
                                'semester'    =>  $semester,
                                'admission_year'    =>  $profileData['std_admyear'],
                                'level_id'    =>  $extraData['stdlevel'],
                                'prog_id'    =>  $profileData['stdprogramme_id'],
                                'prog_type_id'    =>  $profileData['stdprogrammetype_id']
                            ];
                            if (StudentSession::withTrashed()->where($data)->count() === 0) {
                                StudentSession::create($data);
                            }
                        }
                    }
                } else {
                    $semesters = 2;
                    if ($stdprofile->stdprogrammetype_id == 2) $semesters = 3;

                    foreach (range(1, $semesters) as $semester) {
                        $data = [
                            'log_id'    =>  $stdprofile->std_logid,
                            'form_number'   =>  $applogin['log_form_number'],
                            'matric_number'   =>  $applogin['log_username'],
                            'session'    =>  sprintf('%s/%s', $stdprofile->std_admyear, (int)$stdprofile->std_admyear + 1),
                            'semester'    =>  $semester,
                            'admission_year'    =>  $stdprofile->std_admyear,
                            'level_id'    =>  $stdprofile->stdlevel,
                            'prog_id'    =>  $stdprofile->stdprogramme_id,
                            'prog_type_id'    =>  $stdprofile->stdprogrammetype_id
                        ];
                        if (StudentSession::withTrashed()->where($data)->count() === 0) {
                            StudentSession::create($data);
                        }
                    }
                }
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
