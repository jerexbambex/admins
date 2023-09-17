<?php

namespace App\Http\Livewire\Penalties;

use App\Models\SchoolSession;
use App\Models\StudentPenalty;
use App\Models\StudentSession;
use Carbon\Carbon;
use Livewire\Component;

class ReinstatementComponent extends Component
{
    public $penalty_id, $penalty, $session, $with_next;

    protected $rules = [
        'session'   =>  'required|string'
    ];

    public function mount($penalty_id)
    {
        $this->with_next = false;
        $penalty = StudentPenalty::find($penalty_id);
        if (!$penalty) return redirect()->route('dashboard');

        if ($penalty->reinstated_at) return redirect()->route('dashboard');

        $this->penalty = $penalty;
    }

    public function hydrate()
    {
        $this->penalty = StudentPenalty::find($this->penalty_id);
    }

    public function submit()
    {
        $this->validate();

        $this->penalty->reinstated_at = Carbon::now();
        $this->penalty->reinstated_by = auth()->id();
        $this->penalty->reinstated_to = $this->session;

        if ($this->penalty->save()) {

            if ($student = $this->penalty->student) {
                $reinstate_data = [
                    'log_id'    =>  $student->std_logid,
                    'form_number'   =>  $student->matset,
                    'matric_number' =>  $student->matric_no,
                    'level_id'      =>  $this->penalty->level_id,
                    'semester'          =>  $this->penalty->semester_id,
                ];

                $check_data = [
                    'admission_year'    =>  $student->std_admyear,
                    'session'           =>  $this->session,
                    'prog_id'           =>  $student->stdprogramme_id,
                    'prog_type_id'      =>  $student->stdprogrammetype_id,
                ];

                StudentSession::where($check_data)->where('level_id', '!=', $this->penalty->level_id)
                    ->whereNull('deleted_at')
                    ->forceDelete();

                $data = array_merge($reinstate_data, $check_data);
                StudentSession::create($data);
                if ($this->with_next) {
                    $semesters = 2;
                    if ($data['prog_type_id'] == 2) $semesters = 3;

                    $data['level_id'] += 1;
                    foreach (range(1, $semesters) as $semester) {
                        $data['semester'] = $semester;
                        StudentSession::create($data);
                    }
                }

                session()->flash('success_toast', 'Student reinstated!');
            } else {
                session()->flash('warning_toast', 'Student not reinstated!');
            }
        } else {
            session()->flash('error_toast', 'Student not reinstated');
        }

        return redirect()->route('dashboard');
    }

    public function render()
    {
        $sessions = SchoolSession::where('year', '>', explode('/', $this->penalty->session)[0])->get(['session']);
        return view('livewire.penalties.reinstatement-component', compact('sessions'));
    }
}
