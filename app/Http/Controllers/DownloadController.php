<?php

namespace App\Http\Controllers;

use App\Exports\AdmitTemplate;
use App\Exports\ApplicantMigrationExport;
use App\Exports\ApplicantsExport;
use App\Exports\StudentsClearanceExport;
use App\Exports\StudentsDataAdmissionsExport;
use App\Models\ChangeAppProgType;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\User;
use App\Services\AdmissionsService;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DownloadController extends Controller
{
    public function downloadApplicants(
        $session = null,
        $faculty_id = 0,
        $department_id = 0,
        $prog_id = 0,
        $prog_type_id = 0,
        $adm_status = 0,
        $search = ''
    ) {
        ini_set('max_execution_time', '0');

        try {
            $filename = "";
            if ($session) $filename .= "$session - ";
            if ($faculty_id) $filename .= Faculty::find($faculty_id)->faculties_name . " - ";
            if ($department_id) $filename .= Department::find($department_id)->departments_name . " - ";
            if ($prog_id) $filename .= PROGRAMMES[$prog_id] . " - ";
            if ($prog_type_id) $filename .= PROG_TYPES[$prog_type_id] . " - ";
            if (in_array($adm_status, [0, 1])) $filename .= ADM_STATUS[$adm_status] . " - ";
            $filename .= "applicants.xlsx";

            return Excel::download(new ApplicantsExport(
                $session,
                $faculty_id,
                $department_id,
                $prog_id,
                $prog_type_id,
                $adm_status,
                $search
            ), $filename);

            // return response()->download(storage_path("/app/$filename"))->deleteFileAfterSend();
        } catch (\Throwable $th) {
            session()->flash('error_toast', "An error occured while processing download");
        }
        return redirect()->back();
    }

    public function downloadStudents(
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
        try {
            $students = AdmissionsService::getStudents(
                $adm_year,
                $prog_id,
                $prog_type_id,
                $do_id,
                $department_id,
                $fac_id,
                $clear_status,
                $search,
                $date_from,
                $date_to
            )->get();
            $file_name = "all_students_$adm_year";
            if ($clear_status == 0) $file_name = "uncleared_students_$adm_year";
            elseif ($clear_status == 1) $file_name = "cleared_students_$adm_year";

            // session()->flash('success_toast', 'Download in Progress. . .');
            return Excel::download(new StudentsClearanceExport($students, $file_name), "$file_name.xlsx");
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'An error occured');
        }
    }

    public function downloadStudentsData(
        $faculty = 0,
        $department = 0,
        $course = 0,
        $level = 0,
        $adm_year = 2020,
        $programme = 0,
        $programme_type = 0,
        $search = ''
    ) {
        try {
            $students = StudentService::studentsQuery(
                $faculty,
                $department,
                $course,
                $level,
                $adm_year,
                $programme,
                $programme_type,
                $search
            )->get();

            $file_name = "Students Data $adm_year";

            return Excel::download(new StudentsDataAdmissionsExport($students, $file_name), "$file_name.xlsx");
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'An error occured');
        }
    }

    public function download_migration_accepted_list()
    {
        try {
            $lists = ChangeAppProgType::with('applicant')->whereStatus('changed')->get();

            return Excel::download(new ApplicantMigrationExport($lists), "download_migration_accepted_list.xlsx");
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'An error occured');
        }
    }

    public function download_admission_template_data($session_year, $prog_id, $prog_type_id, $search = '')
    {
        try {
            if (!$session_year || !$prog_id || !$prog_type_id) return redirect()->back();

            $search = base64_decode($search);
            $admissionService = new AdmissionsService;
            $applicants = $admissionService->admissionTemplateData($session_year, $prog_id, $prog_type_id, base64_encode($search))->get();
            $prog = PROGRAMMES[$prog_id];
            $prog_type = PROG_TYPES[$prog_type_id];
            return Excel::download(new AdmitTemplate($applicants), "admission template $session_year $prog $prog_type.xlsx");
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'An error occured');
        }
    }

    public function download_student_payments_list(
        $fac_id = 0,
        $dept_id = 0,
        $opt_id = 0,
        $level_id = 0,
        $sess_id = 0,
        $prog_id = 0,
        $progtype_id = 0,
        $semester_id = 0
    ) {
        $user = User::find(auth()->id());
        if ($user->hasRole('lecturer')) return redirect()->back();
        return StudentService::downloadStudentPaymentsList(
            $fac_id,
            $dept_id,
            $opt_id,
            $level_id,
            $sess_id,
            $prog_id,
            $progtype_id,
            $semester_id
        );
    }

    public function download_student_registered_list(
        $fac_id = 0,
        $dept_id = 0,
        $opt_id = 0,
        $level_id = 0,
        $sess_id = 0,
        $prog_id = 0,
        $progtype_id = 0,
        $semester_id = 0
    ) {
        $user = User::find(auth()->id());
        if ($user->hasRole('lecturer')) return redirect()->back();
        return StudentService::downloadRegisteredStudents(
            $fac_id,
            $dept_id,
            $opt_id,
            $level_id,
            $sess_id,
            $prog_id,
            $progtype_id,
            $semester_id
        );
    }
}
