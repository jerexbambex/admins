<?php

namespace App\Http\Livewire\Admission;

use App\Models\Applicant;
use App\Models\Department;
use App\Models\Portal;
use App\Models\ProgrammeType;
use App\Models\SchoolSession;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AdmittedStudents extends Component
{
    public $department_id = 0, $prog_id = 0, $prog_type_id = 0,  $adm_year;

    public $appno, $new_prog_type_id;

    public $search = '', $user_role;

    public function mount()
    {
        $this->adm_year = SchoolSession::latest()->first()->year;
        $this->prog_type_id = auth()->user()->prog_type_id;
        $user = User::find(auth()->user()->id);
        $this->user_role = $user->user_role();
    }

    public function deleteDuplicate(Portal $portal)
    {
        try {
            if ($portal->hasDuplicates() && $portal->delete()) return session()->flash('success_toast', 'Data deleted successfully!');
            return session()->flash('error_toast', 'Unable to perform that action!');
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'Unable to perform that action!');
        }
    }

    public function unAdmit(Portal $portal)
    {
        try {
            $applicant = Applicant::where('app_no', $portal->appno)->first();

            if (!$applicant || $applicant->adm_year <= 2020) {
                return session()->flash('error_toast', 'Unable to perform that action!');
            }

            $applicant->adm_status = 0;
            if ($applicant->save()) {
                $portal->profile()->delete();
                $portal->profile_login()->delete();
                $portal->sessions()->delete();
                $portal->delete();
                return session()->flash('success_toast', 'Student Admission has been reverted successfully!');
            }
            return session()->flash('error_toast', 'Unable to perform that action!');
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'Unable to perform that action!');
        }
    }

    function setNewProgType(Portal $portal)
    {
        try {
            if ($portal && $this->new_prog_type_id) {
                if ($portal->adm_year <= 2020) {
                    $old_progtype = $portal->progtype;
                    $portal->progtype = $this->new_prog_type_id;

                    //Update Profile (ref: stdprofile)
                    $portal->profile()->update([
                        'stdprogrammetype_id' =>  $this->new_prog_type_id
                    ]);

                    // Update sessions if profile exists (ref: student_sessions)
                    if ($profile = $portal->profile()->first()) {
                        $profile->sessions()->update([
                            'prog_type_id' =>   $this->new_prog_type_id
                        ]);
                    }

                    // Log change 
                    $portal->prog_type_change_logs()->create([
                        'old_progtype' => $old_progtype,
                        'new_progtype' => $this->new_prog_type_id,
                        'user_id' => auth()->id(),
                        'form_number' => $portal->appno,
                    ]);

                    //Save portal changes (ref: portalaccess)
                    $portal->save();

                    $this->new_prog_type_id = '';

                    return session()->flash('success_toast', 'Programme type changed successfully!');
                }
            }
            return session()->flash('error_toast', 'Unable to perform that action!');
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'Unable to perform that action!');
        }
    }

    use WithPagination;
    public function render()
    {
        $departments = Department::all(['departments_id', 'departments_name']);
        $portals = Portal::with(['faculty', 'department', 'course']);
        if ($this->prog_id) $portals->where('prog', $this->prog_id);
        if ($this->adm_year) $portals->where('adm_year', $this->adm_year);
        if ($this->prog_type_id) $portals->where('progtype', $this->prog_type_id);
        if ($this->department_id) $portals->where('dept_id', $this->department_id);
        if ($this->user_role == 'revalidation-admin') $portals->where('adm_year', '<', '2020');
        $portals = $portals->where('appno', 'like', '%' . $this->search . '%')->paginate(PAGINATE_SIZE);
        $schSessions = SchoolSession::latest()->get(['year']);
        $programme_types = ProgrammeType::all(['programmet_id', 'programmet_name']);

        return view('livewire.admission.admitted-students', compact('portals', 'departments', 'schSessions', 'programme_types'));
    }
}
