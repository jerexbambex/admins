<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Programme;
use App\Models\SchoolSession;
use Livewire\Component;

class PrintCourseStructureComponent extends Component
{
    public $faculty, $department, $programme, $session_year;

    protected $rules = [
        'faculty'   =>  'required',
        'department'   =>  'required',
        'programme'   =>  'required',
        'session_year'   =>  'required',
    ];

    public function mount()
    {
        $this->faculty = "";
        $this->department = "";
        $this->programme = "";
        $this->session_year = "";
    }

    public function updated($field)
    {
        if ($field == 'faculty') $this->department = "";
        $this->validateOnly($field);
    }

    public function print()
    {
        $this->validate();
        return redirect()->route('print_course_structure', ['dept_id' => $this->department, 'prog_id' => $this->programme, 'session' => $this->session_year]);
    }

    public function render()
    {
        $faculties = Faculty::all(['faculties_id', 'faculties_name']);
        $departments = [];
        if ($this->faculty) $departments = Department::whereFacId($this->faculty)->get(['departments_id', 'departments_name']);
        $programmes = Programme::all(['programme_id', 'programme_name']);
        $sessions = SchoolSession::orderBy('year', 'desc')->get(['year']);

        return view('livewire.print-course-structure-component', compact('faculties', 'departments', 'programmes', 'sessions'));
    }
}
