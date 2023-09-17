<?php

namespace App\Http\Livewire\Student;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Level;
use App\Models\Portal;
use App\Models\Programme;
use App\Models\ProgrammeType;
use App\Models\State;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UpdatePortalAccess extends Component
{
    public $appno;
    public $fullname;
    public $state;
    public $faculty;
    public $department;
    public $programme;
    public $level;
    public $programme_type;
    public $gender;
    public $pid;

    protected function rules()
    {
        return [
            'appno' => [
                'required',
                Rule::unique('portalaccess')->ignore($this->pid, 'pid')
            ],
            'fullname' => 'required',
            'state' => 'required|numeric',
            'faculty' => 'required|numeric',
            'department' => 'required|numeric',
            'level' => 'required|numeric',
            'programme' => 'required|numeric',
            'programme_type' => 'required|numeric',
            'gender' => 'required'
        ];
    }

    public function mount($pid)
    {
        $portal = Portal::where('pid', $pid)->first();
        $this->appno = $portal->appno;
        $this->fullname = $portal->fullname;
        $this->state = $portal->state;
        $this->faculty = $portal->school;
        $this->department = $portal->dcos;
        $this->programme = $portal->prog;
        $this->level = $portal->level;
        $this->programme_type =  $portal->progtype;
        $this->gender = $portal->gender;
        $this->pid = $portal->pid;
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function updatePortal()
    {
        $this->validate();
        $portal = Portal::find($this->pid);
        // $portal->appno = $this->appno;
        $portal->fullname = $this->fullname;
        $portal->state = $this->state;
        $portal->school = $this->faculty;
        $portal->dcos = $this->department;
        $portal->prog = $this->programme;
        $portal->level = $this->level;
        $portal->progtype = $this->programme_type;
        $portal->gender = $this->gender;
        $portal->save();
        session()->flash('message', 'Portal access has been updated');
    }
    public function render()
    {
        $student = Portal::where('pid', $this->pid);
        $programmes = Programme::all();
        $progtypes = ProgrammeType::all();
        $states = State::all();
        $faculties = Faculty::all();
        $levels = [];
        if($this->programme) $levels = Level::where('programme_id', $this->programme)->get();
        $departments = [];
        if ($this->faculty) $departments = Department::where('fac_id', $this->faculty)->get();
        return view(
            'livewire.student.update-portal-access',
            compact('student', 'levels', 'departments',  'programmes', 'progtypes', 'states', 'faculties')
        );
    }
}
