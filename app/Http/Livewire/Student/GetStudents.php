<?php

namespace App\Http\Livewire\Student;

use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Faculty;
use App\Models\Programme;
use App\Models\ProgrammeType;
use App\Models\SchoolSession;
use App\Models\StdLogin;
use App\Models\Student;
use App\Models\User;
use App\Services\StudentService;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class GetStudents extends Component
{
    public $pagesize = 10;

    public $search = '', $user_role, $filterable, $user_programme_type;
    public $faculty, $department, $course, $programme, $programme_type, $session_year;

    use WithPagination;

    public function mount()
    {
        $this->search = '';
        $this->pagesize = 10;
        $user = User::find(auth()->user()->id);
        $this->user_role = $user->user_role();
        $this->filterable = true;
        $this->faculty = $this->department = $this->course =
            $this->programme = $this->programme_type = "0";
        $this->user_programme_type = auth()->user()->prog_type_id;
        if (!$this->user_programme_type) $this->programme_type = $this->user_programme_type;
        $this->session_year = session()->get('app_session');
    }

    public function updated($field)
    {
        if (in_array($field, ['faculty', 'department', 'course', 'programme', 'programme_type']))
            $this->filterable = false;

        if ($field == 'faculty') {
            $this->department = "0";
            $this->course = "0";
            $this->programme = "0";
        } elseif ($field == 'department') {
            $this->course = "0";
            $this->programme = "0";
        } elseif ($field == 'programme') {
            $this->course = "0";
        }
    }


    public function resetPassword(Student $student)
    {
        try {
            // $newPassword = substr(md5(rand()), 0, 6);
            $matric_no = trim($student->matric_no);
            $form_no = trim($student->matset);
            $newPassword = is_numeric($matric_no) ? $form_no : $matric_no;

            if (StdLogin::find($student->std_logid)->update(['log_password' => Hash::make($newPassword)]))
                return session()->flash('success_alert', "Student Password Reset Successful <br> New Password: $newPassword");

            return session()->flash('error_toast', "Unable to perform that action!");
        } catch (\Throwable $th) {
            return session()->flash('error_toast', "Unable to perform that action!");
        }
    }

    public function downloadStudents()
    {
        $level = 0;
        if ($this->programme) $level = $this->programme == 1 ? 1 : 3;
        return redirect()->route('download_students_data', [
            'faculty' => $this->faculty,
            'department' => $this->department,
            'course' => $this->course,
            'level' => $level,
            'adm_year' => $this->session_year,
            'programme' => $this->programme,
            'programme_type' => $this->programme_type,
            'search' => $this->search
        ]);
    }


    public function render()
    {
        $students = $departments = $options = $programmes = $programme_types = [];
        $faculties = Faculty::all(['faculties_id', 'faculties_name']);
        $programmes = Programme::all(['programme_id', 'programme_name']);
        $sessions = SchoolSession::all(['year']);

        if ($this->faculty) //then 
            $departments = Department::whereFacId($this->faculty)->get(['departments_id', 'departments_name']);
        if ($this->department && $this->programme) //then 
            $options = DeptOption::whereDeptId($this->department)->whereProgId($this->programme)->get(['do_id', 'programme_option']);
        if (!$this->user_programme_type) //then
            $programme_types = ProgrammeType::all(['programmet_id', 'programmet_name']);
        else //then
            $programme_types = ProgrammeType::whereProgrammetId($this->programme_type)->get(['programmet_id', 'programmet_name']);

        if ($this->filterable) {
            $level = 0;
            if ($this->programme) $level = $this->programme == 1 ? 1 : 3;
            $students = StudentService::studentsQuery(
                $this->faculty,
                $this->department,
                $this->course,
                $level,
                $this->session_year,
                $this->programme,
                $this->programme_type,
                $this->search
            );
            $students = $students->paginate($this->pagesize);
        }

        return view('livewire.student.get-students', compact(
            'students',
            'departments',
            'faculties',
            'options',
            'programmes',
            'programme_types',
            'sessions'
        ));
    }
}
