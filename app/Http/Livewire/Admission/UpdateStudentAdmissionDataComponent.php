<?php

namespace App\Http\Livewire\Admission;

use App\Models\Applicant;
use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Faculty;
use App\Models\Portal;
use App\Models\Programme;
use App\Models\State;
use App\Models\Student;
use App\Models\StudentSession;
use Livewire\Component;

class UpdateStudentAdmissionDataComponent extends Component
{
    public $pid, $faculty, $department, $programme, $course;
    public $name, $form_number, $gender, $state, $programme_type;

    protected $rules = [
        'faculty'   =>  'required',
        'department'   =>  'required',
        'programme'   =>  'required',
        'programme_type'   =>  'required',
        'course'   =>  'required',
        'gender'    =>  'required',
        'state'     =>  'required'
    ];

    public function mount($param)
    {
        $this->pid = base64_decode($param);

        $portal = Portal::find($this->pid);
        if (!$portal) return redirect()->route('admission.admitted.students');

        $this->faculty = $portal->faculty->faculties_id;
        $this->department = $portal->dept_id;
        $this->programme = $portal->prog;
        $this->course = $portal->dcos;
        $this->name = $portal->fullname;
        $this->form_number = $portal->appno;
        $this->state = $portal->state;
        $this->gender = $portal->gender;
        $this->programme_type = $portal->progtype;
    }

    public function updated($field)
    {
        if ($field == 'faculty') {
            $this->department = "";
            $this->programme = "";
            $this->course = "";
        } elseif (in_array($field, ['department', 'programme'])) {
            $this->course = "";
        }

        return $this->validateOnly($field);
    }

    public function submit()
    {
        $this->validate();

        try {
            $portal = Portal::find($this->pid);
            if ($portal) {
                $portal->update([
                    'dept_id'   =>  $this->department,
                    'dcos'      =>  $this->course,
                    'prog'      =>  $this->programme,
                    'level'     =>  $this->programme == 1 ? 1 : 3,
                    'progtype'  =>  $this->programme_type
                ]);

                $application = Applicant::whereAppNo($this->form_number)->first();
                if ($application) {
                    $application->update([
                        'stdcourse' => $this->course,
                        'stdprogramme_id' => $this->programme,
                        'fac_id' => $this->faculty,
                        'dept_id' => $this->department,
                        'std_programmetype' =>  $this->programme_type
                    ]);
                }

                $student = Student::whereMatricNo($this->form_number)->orWhere('matset', $this->form_number)->first();
                if ($student) {
                    $student->update([
                        'stdfaculty_id' => $this->faculty,
                        'stddepartment_id' => $this->department,
                        'stdcourse' => $this->course,
                        'stdprogramme_id' => $this->programme,
                        'stdprogrammetype_id' => $this->programme_type,
                        'stdlevel' => $this->programme == 1 ? 1 : 3
                    ]);

                    StudentSession::whereFormNumber($this->form_number)->update([
                        'prog_id' => $this->programme,
                        'prog_type_id' => $this->programme_type
                    ]);
                }

                return session()->flash('success_alert', "Update successful!");
            }
            return session()->flash('error_alert', "Unable to perform that action!");
        } catch (\Throwable $th) {
            return session()->flash('error_alert', "Unable to perform that action!");
        }
    }

    public function render()
    {
        $faculties = Faculty::all(['faculties_id', 'faculties_name']);
        $programmes = Programme::all(['programme_id', 'programme_name']);
        $states = State::all();
        $departments = $options = [];
        if ($this->faculty) $departments = Department::whereFacId($this->faculty)->get(['departments_id', 'departments_name']);
        if ($this->programme && $this->department) $options = DeptOption::whereDeptId($this->department)->whereProgId($this->programme)->get(['do_id', 'programme_option']);
        return view(
            'livewire.admission.update-student-admission-data-component',
            compact(
                'faculties',
                'departments',
                'programmes',
                'options',
                'states'
            )
        );
    }
}
