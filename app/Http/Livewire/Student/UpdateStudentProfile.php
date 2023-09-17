<?php

namespace App\Http\Livewire\Student;

use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Faculty;
use App\Models\Programme;
use App\Models\ProgrammeType;
use App\Models\State;
use App\Models\Student;
use App\Models\StudentSession;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UpdateStudentProfile extends Component
{
    public $matric_no;
    public $surname;
    public $firstname;
    public $othernames;
    public $state;
    public $faculty;
    public $department;
    public $course;
    public $programme;
    public $level;
    public $programme_type;
    public $gender;
    public $matset;
    public $email, $phone;
    public $std_logid, $std_id;
    public $birth_date;

    protected function rules()
    {
        return [
            'matric_no' => [
                'required',
                Rule::unique('stdprofile')->ignore($this->std_id, 'std_id')
            ],
            'surname' => 'required',
            'firstname' => 'required',
            'state' => 'required|numeric',
            'faculty' => 'required|numeric',
            'department' => 'required|numeric',
            'level' => 'required|numeric',
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
        $this->std_logid = $student->std_logid;
        $this->matric_no = $student->matric_no;
        $this->surname = $student->surname;
        $this->firstname = $student->firstname;
        $this->othernames = $student->othernames;
        $this->state = $student->state_of_origin;
        $this->faculty = $student->stdfaculty_id;
        $this->department = $student->stddepartment_id;
        $this->course = $student->stdcourse;
        $this->programme = $student->stdprogramme_id;
        $this->level = $student->stdlevel;
        $this->programme_type =  $student->stdprogrammetype_id;
        $this->matset = $student->matset;
        $this->gender = $student->gender;
        $this->std_id = $student->std_id;
        $this->phone = $student->student_mobiletel;
        $this->email = $student->student_email;
        $this->birth_date = $student->birthdate;
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function updateStudent()
    {
        $this->validate();
        $student = Student::find($this->std_id);
        $student->std_logid = $this->std_logid;
        $student->matric_no = $this->matric_no;
        $student->surname = $this->surname;
        $student->firstname = $this->firstname;
        $student->othernames = $this->othernames;
        $student->state_of_origin = $this->state;
        $student->stdfaculty_id = $this->faculty;
        $student->stddepartment_id = $this->department;
        $student->stdcourse = $this->course;
        $student->stdprogramme_id = $this->programme;
        $student->stdlevel = $this->programme == 1 ? 1 : 3;
        $student->stdprogrammetype_id = $this->programme_type;
        $student->gender = $this->gender;
        $student->student_mobiletel = $this->phone;
        $student->student_email = $this->email;
        $student->birthdate = $this->birth_date;
        $student->save();

        $session_update_data = [
            'prog_id'   =>  $this->programme,
            'prog_type_id'  =>  $this->programme_type,
        ];

        $student->login()->update([
            'log_username'  =>  $this->matric_no,
            'log_form_number'   =>  $this->matset,
            'log_surname'   =>  $this->surname,
            'log_firstname' =>  $this->firstname,
            'log_othernames'    =>  $this->othernames,
            'log_email' =>  $this->email
        ]);

        $student->sessions()->update($session_update_data);
        // if ($student->sessions()->count() % 3 == 0 and $this->programme_type != 2) $student->sessions()->whereSemester(3)->delete();
        // if ($student->sessions()->count() % 3 != 0 and $this->programme_type == 2) {
        //     $data = $student->sessions()->first()->toArray();
        //     $data['semester'] = 3;
        //     StudentSession::create($data);
        // }

        return session()->flash('success_toast', 'Student\'s details updated successfully!');
    }


    public function render()
    {
        $student = Student::where('std_logid', $this->std_logid);
        $programmes = Programme::all();
        $progtypes = ProgrammeType::all();
        $states = State::all();
        $faculties = Faculty::all();
        $departments = [];
        if ($this->faculty) $departments = Department::where('fac_id', $this->faculty)->get();
        $courses = [];
        if ($this->department && $this->programme)
            $courses = DeptOption::where('dept_id', $this->department)->where('prog_id', $this->programme)->get();
        // dd($this->department, $this->programme, $courses->toArray());
        return view(
            'livewire.student.update-student-profile',
            compact('student', 'departments', 'courses', 'programmes', 'progtypes', 'states', 'faculties')
        );
    }
}
