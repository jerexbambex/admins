<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\AppLogin;
use App\Models\SchoolSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicantController extends Controller
{
    // public function fetch()
    // {
    //     $applicants = Applicant::with(['dept_option', 'programme', 'progType', 'jamb_detail', 'department', 'olevels'])->withSum('jambs', 'jscore');
    //     if ($this->prog_id) $applicants->where('stdprogramme_id', $this->prog_id);
    //     if ($this->prog_type_id) $applicants->where('std_programmetype', $this->prog_type_id);
    //     if ($this->faculty_id) $applicants->where('fac_id', $this->faculty_id);
    //     if ($this->department_id) $applicants->where('dept_id', $this->department_id);
    //     if($search = $this->search)
    //     $applicants->where(function($query) use($search){
    //         $query->where('app_no', 'like', '%' . $search . '%')
    //         ->orwhere('surname', 'like', '%' . $search . '%')
    //         ->orwhere('firstname', 'like', '%' . $search . '%')
    //         ->orwhere('othernames', 'like', '%' . $search . '%');
    //     });
    //     $ft_slug = $this->ft_slug;
    //     $dpp_slug = $this->dpp_slug;
    //     $cec_slug = $this->cec_slug;

    //     if(!$this->prog_type_id)
    //     $applicants->where(function($query) use ($ft_slug, $dpp_slug, $cec_slug){
    //         $query->where('app_no', 'like', $ft_slug.'%')
    //         ->orwhere('app_no', 'like', $dpp_slug.'%')
    //         ->orwhere('app_no', 'like', $cec_slug.'%');
    //     });

    //     else{
    //         if($this->prog_type_id == 1)
    //         $applicants->where('app_no', 'like', $ft_slug.'%');
    //         elseif($this->prog_type_id == 2)
    //         $applicants->where('app_no', 'like', $cec_slug.'%');
    //         elseif($this->prog_type_id == 3)
    //         $applicants->where('app_no', 'like', $dpp_slug.'%');
    //     }

    //     if($this->prog_type_id) $applicants->where('std_programmetype', $this->prog_type_id);
    //     return $applicants->where('adm_status', $this->adm_status)->orderBy('std_custome9', 'desc');
    // }


    public function fetchAll(Request $request)
    {
        $user = auth()->user();
        // $app_sess = DB::table('app_current_session')->whereStatus('current')->first()->cs_session;
        $app_sess = $request->app_session;
        $paginateSize = $request->paginate_size ?? 25;
        if (!$app_sess) return response()->json(['status' => 'error', "message" => 'APP Session not provided']);
        $ses_slug = str_replace('20', '', (string)$app_sess);
        $ft_slug = "F$ses_slug";
        $dpp_slug = "D$ses_slug";
        $cec_slug = "C$ses_slug";
        $adm_status = $request->adm_status ?? 0;

        $prog_type_id = $user->prog_type_id;

        /**
         * SEARCH PARAM
         */
        $search = null;
        if ($request->search) $search = explode(' ', str_replace(',', '', $request->search));

        $applicants = Applicant::select([
            'app_no', 'surname', 'firstname', 'othernames',
            'dept_id', 'adm_status', 'std_logid', 'std_id',
            'stdprogramme_id', 'std_programmetype', 'stdcourse',
            'student_mobiletel', 'student_email'
        ])->withSum('jambs', 'jscore');


        if ($search) {
            if (count($search) == 2) $applicants->where(function ($query) use ($search) {
                $query->where('surname', 'like', '%' . $search[0] . '%')
                    ->where('firstname', 'like', '%' . $search[1] . '%');
            });
            elseif (count($search) == 3) $applicants->where(function ($query) use ($search) {
                $query->where('surname', 'like', '%' . $search[0] . '%')
                    ->where('firstname', 'like', '%' . $search[1] . '%')
                    ->where('othernames', 'like', '%' . $search[2] . '%');
            });
            else $applicants->where(function ($query) use ($search) {
                $query->where('app_no', 'like', '%' . $search[0] . '%')
                    ->orwhere('surname', 'like', '%' . $search[0] . '%')
                    ->orwhere('firstname', 'like', '%' . $search[0] . '%')
                    ->orwhere('othernames', 'like', '%' . $search[0] . '%');
            });
        }

        if (!$prog_type_id) {
            $applicants->where(function ($query) use ($ft_slug, $dpp_slug, $cec_slug) {
                $query->where('app_no', 'like', $ft_slug . '%')
                    ->orwhere('app_no', 'like', $dpp_slug . '%')
                    ->orwhere('app_no', 'like', $cec_slug . '%');
            });
        } else {
            if ($prog_type_id == 1)
                $applicants->where('app_no', 'like', $ft_slug . '%');
            elseif ($prog_type_id == 2)
                $applicants->where('app_no', 'like', $cec_slug . '%');
            elseif ($prog_type_id == 3)
                $applicants->where('app_no', 'like', $dpp_slug . '%');
        }

        $applicants = $applicants->where('adm_status', $adm_status)->orderBy('std_custome9', 'desc')->paginate($paginateSize);
        // $applicants = $applicants->where('adm_status', $adm_status)->orderBy('std_custome9', 'desc')->get();

        return response()->json(['status' => 'success', 'message' => 'successful', "data" => $applicants]);
    }

    public function resetPassword(Applicant $applicant)
    {
        $newPassword = trim($applicant->app_no);

        if (AppLogin::find($applicant->std_logid)->update(['log_password' => bcrypt($newPassword)]))
            return response()->json(['status' => 'success', 'message' => "Student Password Reset Successful <br> New Password: $newPassword"]);

        return response()->json(['status' => 'error', 'message' => 'Unable to reset applicant\'s password!']);
    }

    public function admitApplicant(Applicant $applicant)
    {
        $adm_year = SchoolSession::latest()->first()->year;
        $data = [
            'appno' =>  $applicant->app_no,
            'fullname' =>  $applicant->full_name,
            'gender' =>  $applicant->gender,
            'dcos' =>  $applicant->dept_id,
            'school' =>  $applicant->fac_id,
            'state' =>  $applicant->state_of_origin,
            'prog' =>  $applicant->stdprogramme_id,
            'progtype' =>  $applicant->std_programmetype,
            'level' =>  $applicant->stdprogramme_id == 1 ? 1 : 3,
            'adm_year' =>  $adm_year
        ];

        $applicant->adm_status = 1;
        $applicant->date_admitted = now();
        if (DB::table('portalaccess')->insert($data) && $applicant->save())
        return response()->json(['status' => 'success', 'message' => "Student: $applicant->app_no admitted successfully!"]);

        return response()->json(['status' => 'error', 'message' => "Unable to admit student: $applicant->app_no!"]);
    }
}
