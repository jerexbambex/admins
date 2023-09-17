<?php

namespace App\Http\Livewire\Admission;

use App\Models\Applicant;
use App\Models\AppLogin;
use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Faculty;
use App\Models\Portal;
use App\Models\Programme;
use App\Models\ProgrammeType;
use App\Models\State;
use App\Models\Student;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditApplicantsComponent extends Component
{
    public $app_no;
    public $surname;
    public $firstname;
    public $othernames;
    public $soo;
    public $faculty;
    public $department;
    public $course;
    public $programme;
    public $programme_type;
    public $gender;
    public $std_logid, $std_id;
    public $email;

    protected function rules()
    {
        return [
            'app_no' => [
                'required',
                Rule::unique('application_profile')->ignore($this->std_id, 'std_id')
            ],
            'surname' => 'required',
            'firstname' => 'required',
            'soo' => 'required',
            'faculty' => 'required',
            'department' => 'required',
            'course' => 'required',
            'programme' => 'required',
            'programme_type' => 'required',
            'gender' => 'required',
            'email' =>  'required'
        ];
    }

    public function mount($std_logid)
    {
        $applicant = Applicant::where('std_logid', $std_logid)->first();
        $this->std_logid = $applicant->std_logid;
        $this->app_no = $applicant->app_no;
        $this->surname = $applicant->surname;
        $this->firstname = $applicant->firstname;
        $this->othernames = $applicant->othernames;
        $this->soo = $applicant->state_of_origin ?? 0;
        $this->faculty = $applicant->fac_id;
        $this->department =  $applicant->dept_id;
        $this->course = $applicant->stdcourse;
        $this->programme = $applicant->stdprogramme_id;
        $this->programme_type =  $applicant->std_programmetype;
        $this->gender = $applicant->gender;
        $this->std_id = $applicant->std_id;
        $this->email = $applicant->student_email;
    }

    public function updated($fields)
    {
        if ($fields == 'faculty') {
            $this->department = "";
            $this->course = "";
        } elseif ($fields == 'department' || $fields == 'programme') {
            $this->course = "";
        }
        $this->validateOnly($fields);
    }

    public function updateApplicant()
    {
        $this->validate();

        try {
            $applicant = Applicant::find($this->std_id);
            if (!$applicant)  return session()->flash('error_toast', "Invalid action!");

            $applicant->std_logid = $this->std_logid;
            $applicant->surname = $this->surname;
            $applicant->firstname = $this->firstname;
            $applicant->othernames = $this->othernames;
            $applicant->state_of_origin = $this->soo;
            $applicant->dept_id = $this->department;
            $applicant->fac_id = $this->faculty;
            $applicant->stdcourse = $this->course;
            $applicant->stdprogramme_id = $this->programme;
            $applicant->std_programmetype = $this->programme_type;
            $applicant->gender = $this->gender;
            $applicant->student_email = $this->email;
            $applicant->save();

            AppLogin::whereLogId($applicant->std_logid)->update([
                'log_username'  =>  $this->email
            ]);

            if ($portal = Portal::whereAppno($applicant->app_no)->first()) {
                $portal->update([
                    'fullname'  =>  "$this->surname $this->firstname $this->othernames",
                    'gender'    =>  $this->gender,
                    'dept_id'   =>  $this->department,
                    'dcos'      =>  $this->course,
                    'school'    =>  $this->faculty,
                    'prog'      =>  $this->programme,
                    'progtype'  =>  $this->programme_type,
                    'level'     =>  $this->programme == 1 ? 1 : 3
                ]);
            }

            if ($student = Student::whereMatricNo($applicant->app_no)->orWhere('matset', $applicant->app_no)->first()) {
                $student->update([
                    'surname' => $this->surname,
                    'firstname' => $this->firstname,
                    'othernames'  => $this->othernames,
                    'state_of_origin' => $this->soo,
                    'stdfaculty_id' => $this->faculty,
                    'stddepartment_id'  => $this->department,
                    'stdcourse' => $this->course,
                    'stdprogramme_id' => $this->programme,
                    'stdlevel'  => $this->programme == 1 ? 1 : 3,
                    'stdprogrammetype_id' => $this->programme_type,
                    'gender' => $this->gender,
                    'student_email' =>  $this->email
                ]);

                $student->login()->update([
                    'log_surname' => $this->surname,
                    'log_firstname' => $this->firstname,
                    'log_othernames'  => $this->othernames,
                ]);
            }
            return session()->flash('success_toast', 'Applicant detail has been updated');
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'An error encountered');
        }
    }

    public function render()
    {
        // $applicant = Applicant::where('std_logid', $this->std_logid);
        $programmes = Programme::all(['programme_id', 'programme_name']);
        $progtypes = ProgrammeType::all(['programmet_id', 'programmet_name']);
        $states = State::all();
        $faculties = Faculty::all(['faculties_id', 'faculties_name']);
        $departments = $courses = [];
        if ($this->faculty) $departments = Department::where('fac_id', $this->faculty)->get(['departments_id', 'departments_name']);
        if ($this->department && $this->programme)
            $courses = DeptOption::where('dept_id', $this->department)->where('prog_id', $this->programme)->get(['do_id', 'programme_option']);
        return view(
            'livewire.admission.edit-applicants-component',
            compact('departments', 'courses', 'programmes', 'progtypes', 'states', 'faculties')
        );
    }
}
