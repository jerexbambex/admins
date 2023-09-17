<?php

namespace App\Http\Livewire;

use App\Models\Course;
use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Faculty;
use App\Models\Level;
use App\Models\Programme;
use App\Models\ProgrammeType;
use App\Models\SchoolSession;
use App\Services\ResultRevalidationService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class ResultRevalidationComponent extends Component
{
    public $faculty_id, $department_id, $dept_option_id;
    public $programme_id, $programme_type;
    public $level_id, $course_id, $semester;
    public $session, $admission_year;

    protected $rules = [
        'faculty_id'        =>  'required',
        'department_id'     =>  'required',
        'dept_option_id'    =>  'required',
        'programme_id'      =>  'required',
        'programme_type'    =>  'required|numeric',
        'session'           =>  'required',
        'admission_year'    =>  'required|numeric',
        'course_id'         =>  'required',
        'level_id'          =>  'required',
        'semester'          =>  'required'
    ];

    protected $messages = [
        'faculty_id.required'        =>  'Please select a faculty',
        'department_id.required'     =>  'Please select a department',
        'dept_option_id.required'    =>  'Please select a dept. option',
        'programme_id.required'      =>  'Please select a programme',
        'level_id.required'          =>  'Please select a level',
        'course_id.required'          =>  'Please select a course'
    ];

    function mount()
    {
        $this->faculty_id = $this->department_id = $this->dept_option_id = '';
        $this->programme_id = $this->programme_type = $this->semester = '';
        $this->level_id = $this->course_id = '';

        $this->session = session()->get('sch_session');
        $this->admission_year = session()->get('app_session');

        Cache::forget('departments');
        Cache::forget('dept_options');
    }

    function updated($field)
    {
        if (in_array($field, ['faculty_id', 'department_id', 'programme_id'])) {
            if ($field === 'faculty_id') {
                Cache::forget('departments');
                $this->department_id = '';
            }

            Cache::forget('dept_options');
            $this->dept_option_id = '';
        } elseif ($field === 'session') {
            session()->put('sch_session', $this->session);
        } elseif ($field === 'admission_year') {
            session()->put('app_session', $this->admission_year);
        }
        // elseif ($field === 'programme_id') {
        //     Cache::forget('levels');
        //     $this->level_id = '';
        // }

        $this->validateOnly($field);
    }

    function download(ResultRevalidationService $resultRevalidationService)
    {
        return $resultRevalidationService->download(
            $this->course_id,
            $this->session,
            $this->semester,
            $this->level_id
        );
    }

    public function render()
    {
        $school_sessions = Cache::remember('school_sessions', 3600, function () {
            return SchoolSession::all(['year', 'session']);
        });

        $faculties = Cache::rememberForever('faculties', function () {
            return Faculty::all(['faculties_id', 'faculties_name']);
        });

        $departments = Cache::remember('departments', 3600, function () {
            return Department::whereFacId($this->faculty_id)->get(['departments_id', 'departments_name']);
        });

        $dept_options = Cache::remember('dept_options', 3600, function () {
            return DeptOption::whereDeptId($this->department_id)->whereProgId($this->programme_id)->get(['do_id', 'programme_option']);
        });

        $programmes = Cache::rememberForever('programmes', function () {
            return Programme::all(['programme_name', 'programme_id']);
        });

        $programmeTypes = Cache::rememberForever('programmeTypes', function () {
            return ProgrammeType::all(['programmet_name', 'programmet_id']);
        });

        $levels = Level::whereProgrammeId($this->programme_id)->get(['level_id', 'level_name']);

        $semesters = $this->programme_type == 2 ? range(1, 3) : range(1, 2);

        $resultRevalidationService = new ResultRevalidationService();

        $courses = $resultRevalidationService->getCourses($this->admission_year, $this->dept_option_id, $this->programme_type, $this->level_id, $this->semester);

        $course = $resultRevalidationService->getCourse($this->course_id);

        $course_regs = $resultRevalidationService->getCourseRegs($this->course_id, $this->session, $this->semester, $this->level_id);

        return view(
            'livewire.result-revalidation-component',
            compact(
                'school_sessions',
                'faculties',
                'departments',
                'programmes',
                'dept_options',
                'programmeTypes',
                'levels',
                'semesters',
                'courses',
                'course',
                'course_regs'
            )
        );
    }
}
