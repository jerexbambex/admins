<?php

namespace App\Http\Livewire\Results;

use App\Models\Course;
use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Faculty;
use App\Models\Level;
use App\Models\Programme;
use App\Models\ProgrammeType;
use App\Models\ResultEditPermission;
use App\Models\SchoolSession;
use App\Models\StudentResult;
use App\Models\StudentResultsConfig;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ResultProcessingComponent extends Component
{
    public $faculty_id, $department_id, $option_id, $programme_id,
        $level_id, $semester_id, $sch_session, $prog_type, $set_year,
        $course_id;

    public $printable = false, $user = null,
        $editable_config = false, $date_from, $date_to;



    protected $rules = [
        'faculty_id' => 'required',
        'department_id' => 'required',
        'option_id' => 'required',
        'programme_id' => 'required',
        'level_id' => 'required',
        'semester_id' => 'required',
        'sch_session' => 'required',
        'set_year' => 'required'
    ];

    public function mount()
    {
        $this->faculty_id = "0";
        $this->department_id = "0";
        $this->option_id = "0";
        $this->programme_id = "0";
        $this->prog_type = 1;
        $this->level_id = "0";
        $this->semester_id = 1;
        $this->sch_session = session()->get('sch_session');
        $this->set_year = session()->get('app_session');
        $this->user = User::find(auth()->user()->id);
        $this->printable = false;
        $user = auth()->user();
        if($user->faculty_id) $this->faculty_id = $user->faculty_id;

        $this->get_editable_config();
    }

    public function get_editable_config()
    {
        $editable = false;
        $session_year = explode('/', $this->sch_session)[0];
        $configs = StudentResultsConfig::select(['bos_moderation_start_date', 'bos_moderation_end_date'])
            ->firstWhere('session_year', $session_year);

        if ($configs) {
            $today = Carbon::now();
            if ($configs->bos_moderation_start_date && $configs->bos_moderation_end_date) {
                $start = Carbon::parse($configs->bos_moderation_start_date);
                $end = Carbon::parse($configs->bos_moderation_end_date);

                if ($today->gte($start) && $today->lte($end)) $editable = true;
            }
        }
        $this->editable_config = $editable;
    }

    public function hydrate()
    {
        $this->user = User::find(auth()->user()->id);
        $this->get_editable_config();
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
        } else {
            if (!in_array($field, ['course_id', 'date_from', 'date_to']))
                $this->printable = false;
        }

        $this->validateOnly($field);
    }

    public function submit()
    {
        $this->validate();

        $this->printable = true;
    }

    public function approveResult()
    {
        $this->validate();

        if ($this->editable_config && $this->course_id && $this->user->hasRole('rector')) {
            if (StudentResult::join('stdprofile', 'stdprofile.std_logid', 'student_results.log_id')
                ->whereLevelId($this->level_id)
                ->whereBosApproved(0)
                ->where('presentation', '!=', 0)
                ->whereProgTypeId($this->prog_type)
                ->whereSemester($this->semester_id)
                ->whereRaw("session = '$this->sch_session'")
                ->where('stdprofile.stdprogramme_id', $this->programme_id)
                ->where('stdprofile.stdprogrammetype_id', $this->prog_type)
                ->where('stdprofile.stdcourse', $this->option_id)
                ->update([
                    'bos_approved'  =>  '1',
                    'rector_id'     =>  auth()->user()->id,
                    'date_approved' =>  Carbon::now()
                ])
            )
                return session()->flash('success_toast', 'Result approved!');
        }

        return session()->flash('error_toast', 'Unable to perform that action!');
    }

    public function reviewResult()
    {
        $this->validate();
        if ($this->editable_config && $this->course_id && $this->date_from && $this->date_to) {
            $date_from = Carbon::parse($this->date_from);
            $date_to = Carbon::parse($this->date_to);

            if ($date_from->gte($date_to))
                return session()->flash('error_toast', 'Invalid date format selected!');

            $result = StudentResult::join('stdprofile', 'stdprofile.std_logid', 'student_results.log_id')
                ->whereLevelId($this->level_id)
                ->whereProgTypeId($this->prog_type)
                ->whereSemester($this->semester_id)
                ->whereRaw("session = '$this->sch_session'")
                ->where('stdprofile.stdprogramme_id', $this->programme_id)
                ->where('stdprofile.stdprogrammetype_id', $this->prog_type)
                ->where('stdprofile.stdcourse', $this->option_id)
                ->whereCourseId($this->course_id)->latest()->first(['lecturer_course_id']);

            if (ResultEditPermission::create([
                'lecturer_course_id'    =>  $result->lecturer_course_id,
                'date_from' =>  $date_from,
                'date_to'   =>  $date_to,
                'approved_by'   =>  auth()->user()->id
            ]))
                return session()->flash('success_toast', 'Result review enabled');
        }
        return session()->flash('error_toast', 'Unable to perform that action!');
    }

    use WithPagination;

    public function render()
    {
        $courses = $departments = $options = $programmes = $levels = [];

        $faculties = Faculty::all('faculties_id', 'faculties_name');
        $programmes = Programme::all(['programme_id', 'programme_name']);
        $programme_types = ProgrammeType::all(['programmet_id', 'programmet_name']);
        $sessions = SchoolSession::all(['year', 'session']);
        if ($this->faculty_id) $departments = Department::whereFacId($this->faculty_id)->get(['departments_id', 'departments_name']);
        if ($this->department_id && $this->programme_id)
            $options = DeptOption::whereDeptId($this->department_id)->whereProgId($this->programme_id)->get(['do_id', 'programme_option']);
        if ($this->programme_id) $levels = Level::whereProgrammeId($this->programme_id)->get(['level_id', 'level_name']);

        if ($this->level_id && $this->option_id && $this->semester_id && $this->sch_session && $this->prog_type && $this->set_year) {
            $semester = SEMESTERS[$this->semester_id];
            $for_cec = $this->prog_type == 2 ? 1 : 0;

            $courses = Course::whereLevels($this->level_id)
                ->whereStdcourse($this->option_id)
                ->whereRaw("semester = '$semester'")
                ->where('for_set', 'like', "%$this->set_year%")
                ->whereForCec($for_cec)
                ->get(['thecourse_id', 'thecourse_title', 'thecourse_code']);
        }

        return view(
            'livewire.results.result-processing-component',
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
