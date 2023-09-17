<?php

namespace App\Http\Livewire\Penalties;

use App\Models\SchoolSession;
use App\Models\Student;
use App\Models\StudentPenalty;
use App\Models\StudentSession;
use Carbon\Carbon;
use Livewire\Component;

class ReinstatementFromIndefinitePenaltyComponent extends Component
{
    public $std_no;

    public function mount()
    {
        $this->std_no = '';
    }

    public function validateStudent()
    {
        $this->validate(['std_no' => 'required']);

        try {
            if ($this->std_no) {
                $student = Student::whereMatricNo($this->std_no)->orWhere('matset', $this->std_no)
                    ->first(['status', 'std_logid']);

                if (!$student) {
                    return session()->flash('error_toast', "Student does not exist!");
                } elseif ($student->status === 'active') {
                    return session()->flash('error_toast', "Student status is `$student->status`!");
                }

                StudentPenalty::whereNull('session')->whereNull('semester_id')->whereNull('level_id')->whereLogId($student->std_logid)->update([
                    'reinstated_by' =>  auth()->id(),
                    'reinstated_at' =>  Carbon::now(),
                ]);
                Student::whereStdLogid($student->std_logid)->update(['status'  =>  'active']);

                session()->flash('success_toast', "Student successfully reinstated!");

                return $this->mount();
            }

            return session()->flash('error_toast', "Unable to perform that action!");
        } catch (\Throwable $th) {
            return session()->flash('error_toast', "Unable to perform that action!");
        }
    }

    public function render()
    {
        return view('livewire.penalties.reinstatement-from-indefinite-penalty-component');
    }
}
