<?php

namespace App\Http\Livewire;

use App\Models\DeptOption;
use App\Models\Student;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditStudentProfile extends Component
{
    public $matric_no;
    public $surname;
    public $firstname;
    public $othernames;
    public $state;
    public $department;
    public $course;
    public $programme;
    public $level;
    public $programme_type;
    public $gender;
    public $matset;
    public $std_logid;

    protected function rules(){
        return [
            'surname' => 'required', 
            'matric_no' => [
                'required',
                Rule::unique('stdprofile')->ignore($this->std_logid)
            ], 
            'firstname' => 'required', 
            'othernames' => 'required', 
            'state' => 'required|numeric', 
            'department' => 'required|numeric',
            'level' => 'required|numeric' ,
            'course' => 'required|numeric', 
            'programme' => 'required|numeric', 
            'programme_type' => 'required|numeric', 
            'matset' => 'required', 
            'gender' => 'required'
        ];
    }

    public function mount($std_logid)
    {
        $student = Student::where('std_logid', $std_logid)->first();
        $this->matric_no = $student->matric_no;
        $this->surname = $student->surname;
        $this->firstname = $student->firstname;
        $this->othernames = $student->othernames;
        $this->state = $student->state;
        $this->department = $student->department;
        $this->course = $student->course;
        $this->programme = $student->programme;
        $this->level = $student->level;
        $this->programme_type = $student->programme_type;
        $this->matset = $student->matset;
        $this->gender = $student->gender;
        $this->std_logid = $student->std_logid;
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function updateStudent()
    {
        $this->validate();

        $student = Student::where('std_logid', $this->std_logid)->first();
        $student->matric_no = $this->matric_no;
        $student->surname = $this->surname;
        $student->firstname = $this->firstname;
        $student->othernames = $this->othernames;
        $student->state_of_origin = $this->state;
        $student->stddepartment_id = $this->department;
        $student->stdcourse = $this->course;
        $student->stdprogramme_id = $this->programme;
        $student->stdlevel = $this->level;
        $student->stdprogrammetype_id= $this->programme_type;
        $student->gender = $this->gender;
        $student->save();

        //I think it should also update portalaccess for future reference
    }



    public function render()
    {
        $department_options = [];
        if($this->department && $this->programme) $department_options = DeptOption::where('dept_id', $this->department)->where('prog_id', $this->programme)->get();
        return view('livewire.edit-student-profile', compact('department_options'));
    }
}

