<?php

namespace App\Http\Livewire\Admission;

use App\Models\Applicant;
use App\Models\ChangeAppProgType;
use App\Models\ProgrammeType;
use Livewire\Component;

class ChangeApplicantProgrammeTypeComponent extends Component
{
    public $appno, $new_prog_type_id, $applicant_id;

    public $is_accepted_list = false, $search;

    public function mount()
    {
        $this->is_accepted_list = false;
        $this->search = '';
        $this->applicant_id = 0;
        $this->new_prog_type_id = 0;
        $this->appno = '';
    }

    public function searchApplicant()
    {
        $applicant = Applicant::whereAppNo($this->appno)->first();
        if ($applicant) $this->applicant_id = $applicant->std_id;
    }

    public function changeListType()
    {
        $this->is_accepted_list = !$this->is_accepted_list;
    }

    public function setNewProgType()
    {
        try {
            $applicant = Applicant::find($this->applicant_id);
            if ($applicant) {
                ChangeAppProgType::create([
                    'applicant_id'  =>  $applicant->std_id,
                    'initial_prog_type' =>  $applicant->std_programmetype,
                    'new_prog_type' =>  $this->new_prog_type_id,
                    'initial_appno' =>  $applicant->app_no
                ]);

                session()->flash('success_alert', 'Programme Type Changed Successfully \n Notification will be sent to Applicant to confirm.');

                $this->mount();
            }
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'An error occured');
        }
    }

    public function reverse(ChangeAppProgType $changeAppProgType)
    {
        try {
            $changeAppProgType->applicant->app_login()->update(['log_username' => $changeAppProgType->initial_appno]);
            $changeAppProgType->applicant->admission_data()->delete();
            $changeAppProgType->update(['new_appno' => null, 'status' => 'pending']);
            $changeAppProgType->applicant()->update([
                'app_no' => $changeAppProgType->initial_appno,
                'std_programmetype' => $changeAppProgType->initial_prog_type,
                'adm_status'    =>  0
            ]);

            session()->flash('success_alert', 'Reversed successfully.');

            $this->mount();
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'An error occured');
        }
    }

    public function downloadAccepted()
    {
        return redirect()->route('download_migration_accepted');
    }

    public function render()
    {
        $applicant = null;
        if ($this->applicant_id) $applicant = Applicant::find($this->applicant_id);
        $programme_types = ProgrammeType::all();

        $status = $this->is_accepted_list ? 'changed' : 'pending';
        $list = ChangeAppProgType::with('applicant')->whereStatus($status);

        if ($this->search)
            $list->where(function ($query) {
                $query->whereInitialAppno($this->search)->orWhere('new_appno', $this->search);
            });

        $list = $list->paginate(20);
        return view('livewire.admission.change-applicant-programme-type-component', compact('applicant', 'programme_types', 'list'));
    }
}
