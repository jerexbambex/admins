<?php

namespace App\Http\Livewire\Results;

use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Faculty;
use App\Models\LecturerCourse;
use App\Models\Level;
use App\Models\Programme;
use App\Models\ProgrammeType;
use App\Models\SchoolSession;
use Livewire\Component;
use Livewire\WithPagination;

class AssignedAndSubmittedReportComponent extends Component
{
    public $faculty_id, $department_id, $option_id, $programme_id,
        $level_id, $semester_id, $session_year, $set_year, $prog_type;

    public $l_course = null;

    private $coursesQuery;

    protected function rules(){
        return [
            'faculty_id' => 'required',
            'department_id' => 'required',
            'option_id' => 'required',
            'programme_id' => 'required',
            'level_id' => 'required',
            'semester_id' => 'required',
            'session_year' => 'required',
            'set_year' => 'required'
        ];
    }

    public function mount()
    {
        $this->faculty_id = "0";
        $this->department_id = "0";
        $this->option_id = "0";
        $this->programme_id = "0";
        $this->level_id = "0";
        $this->semester_id = 1;
        $this->prog_type = "0";
        $this->session_year = session()->get('app_session');
        $this->set_year = session()->get('app_session');
        $user = auth()->user();
        if($user->faculty_id) $this->faculty_id = $user->faculty_id;

        $this->coursesQuery = null;
        $this->l_course = null;
    }

    public function hydrate()
    {
        $this->filterReport();
    }

    public function updated($field)
    {
        if ($field == 'faculty_id') {
            $this->department_id = "0";
            $this->option_id = "0";
        } elseif ($field == 'department_id') {
            $this->option_id = "0";
        } elseif ($field == 'programme_id') {
            $this->option_id = "0";
            $this->level_id = "0";
        }
        $this->validateOnly($field);
    }

    public function filterReport()
    {
        $this->validate();

        $semester = SEMESTERS[$this->semester_id];
        $for_cec = $this->prog_type == 2 ? 1 : 0;

        $query = LecturerCourse::with(['course', 'lecturer', 'programmeType'])
            ->join('courses', 'courses.thecourse_id', 'lecturer_courses.course_id')
            ->whereRaw("courses.theschool_id = $this->faculty_id")
            ->whereRaw("courses.department_id = $this->department_id")
            ->whereRaw("courses.stdcourse = $this->option_id")
            ->whereRaw("courses.levels = $this->level_id")
            ->whereRaw("courses.semester = '$semester'")
            ->whereRaw("courses.for_cec = $for_cec")
            ->whereRaw("courses.for_set like '%$this->set_year%'")
            ->whereSessionYear($this->session_year);
        if ($this->prog_type)
            $query->whereProgrammeTypeId($this->prog_type);
        $this->coursesQuery = $query->orderBy('courses.thecourse_id', 'asc');
    }

    public function viewLecturer(LecturerCourse $lecturerCourse)
    {
        if ($lecturerCourse) {
            $this->l_course = $lecturerCourse;
            $this->dispatchBrowserEvent('lecturer_details_fetched');
        }
    }

    use WithPagination;

    public function render()
    {
        $courses = $departments = $options = $programmes = $levels = [];
        if ($this->coursesQuery <> null)
            $courses = $this->coursesQuery->paginate(PAGINATE_SIZE);

        $faculties = Faculty::all('faculties_id', 'faculties_name');
        $programmes = Programme::all(['programme_id', 'programme_name']);
        $programme_types = ProgrammeType::all(['programmet_id', 'programmet_name']);
        $sessions = SchoolSession::all(['year', 'session']);
        if ($this->faculty_id) $departments = Department::whereFacId($this->faculty_id)->get(['departments_id', 'departments_name']);
        if ($this->department_id && $this->programme_id)
            $options = DeptOption::whereDeptId($this->department_id)->whereProgId($this->programme_id)->get(['do_id', 'programme_option']);
        if ($this->programme_id) $levels = Level::whereProgrammeId($this->programme_id)->get(['level_id', 'level_name']);

        return view(
            'livewire.results.assigned-and-submitted-report-component',
            compact(
                'faculties',
                'departments',
                'options',
                'programmes',
                'programme_types',
                'levels',
                'sessions',
                'courses'
            )
        );
    }
}
