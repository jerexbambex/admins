<?php

namespace App\Http\Livewire\Admission;

use App\Exports\ApplicantsExport;
use App\Models\AppCurrentSession;
use App\Models\Applicant;
use App\Models\AppLogin;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\SchoolSession;
use App\Models\User;
use App\Services\AdmissionsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ViewApplicantsComponent extends Component
{
    public $prog_id = 0, $prog_type_id = 0, $adm_status = 3;

    public $faculty_id = 0, $department_id = 0;

    public $search = '', $app_sess, $filterable, $user_role, $user_prog_type;

    use WithPagination;

    public function mount()
    {
        $user = User::find(auth()->user()->id);
        $this->prog_type_id = $user->prog_type_id;
        $this->user_prog_type = $user->prog_type_id;
        $this->user_role = $user->user_role();
        $this->app_sess = AppCurrentSession::select(['cs_session'])->whereStatus('current')->groupBy('cs_session')->first(['cs_session'])->cs_session;
        $this->filterable = true;
    }

    public function updated($field)
    {
        if (in_array($field, ['faculty_id', 'department_id', 'prog_id', 'prog_type_id', 'adm_status', 'app_sess'])) {
            $this->filterable = false;
        }
    }

    // public function download()
    // {
    //     return redirect()->route('download_applicants', [
    //         'session'   =>  $this->app_sess,
    //         'faculty_id'    =>  $this->faculty_id,
    //         'department_id' =>  $this->department_id,
    //         'prog_id'   =>  $this->prog_id,
    //         'prog_type_id'  =>  $this->prog_type_id,
    //         'adm_status'    =>  $this->adm_status,
    //         'search'    =>  $this->search
    //     ]);
    // }

    // public function download_all()
    // {
    //     return redirect()->route('download_applicants', [
    //         'session'   =>  $this->app_sess,
    //         'faculty_id'    =>  0,
    //         'department_id' =>  0,
    //         'prog_id'   =>  $this->prog_id,
    //         'prog_type_id'  =>  $this->prog_type_id,
    //         'adm_status'    =>  $this->adm_status,
    //         'search'    =>  ''
    //     ]);
    // }

    public function resetPassword(Applicant $applicant)
    {
        try {
            $newPassword = substr(md5(rand()), 0, 6);

            if (AppLogin::find($applicant->std_logid)->update(['log_password' => Hash::make($newPassword)])) {
                return session()->flash('success_alert', "Student Password Reset Successful <br> New Password: $newPassword");
            }
            return session()->flash('error_toast', "Unable to perform that action!");
        } catch (\Throwable $th) {
            return session()->flash('error_toast', "Unable to perform that action!");
        }
    }

    public function admitApplicant(Applicant $applicant)
    {
        try {
            if (AdmissionsService::admitStudent($applicant)) {
                return session()->flash('success_toast', "Student: $applicant->app_no admitted successfully!");
            }
            session()->flash('error_toast', "Unable to admit student: $applicant->app_no!");
        } catch (\Throwable $th) {
            session()->flash('error_toast', "Unable to admit student: $applicant->app_no!");
        }
    }

    function resetData($type = '', $applicant_id)
    {
        try {
            $applicant = Applicant::find($applicant_id);
            if ($type && $applicant) {
                switch ($type) {
                    case 'std_photo':
                        $data = [
                            'std_photo' => '',
                            'biodata' => '0',
                            'std_custome8'  => '0',
                            'std_custome9'  => '0',
                        ];
                        break;

                    default:
                        $data = [
                            $type => '0',
                            'std_custome8'  => '0',
                            'std_custome9'  => '0',
                        ];
                        break;
                }

                if ($applicant->update($data)) {
                    return session()->flash('success_toast', 'Successful!');
                }
            }

            return session()->flash('error_toast', 'Failed!');
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'Failed!');
        }
    }

    public function render()
    {
        $faculties = Faculty::select(['faculties_id', 'faculties_name'])->get();
        $departments = $applicants = [];
        if ($this->faculty_id) $departments = Department::select(['departments_id', 'departments_name'])->whereFacId($this->faculty_id)->get();
        $sessions = AppCurrentSession::select(['cs_session'])->groupBy('cs_session')->get();

        if ($this->filterable) $applicants = AdmissionsService::getApplicants(
            $this->app_sess,
            $this->faculty_id,
            $this->department_id,
            $this->prog_id,
            $this->prog_type_id,
            $this->adm_status,
            $this->search
        )->paginate(PAGINATE_SIZE);

        return view('livewire.admission.view-applicants-component', compact('applicants', 'departments', 'faculties', 'sessions'));
    }
}
