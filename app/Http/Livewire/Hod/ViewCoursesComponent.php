<?php

namespace App\Http\Livewire\Hod;

use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Course;
use App\Models\Level;
use App\Models\Programme;
use App\Models\SchoolSession;
use Livewire\Component;
use Livewire\WithPagination;

class ViewCoursesComponent extends Component
{
    public $search_semester, $search_level, $programme, $option;

    public $sch_session, $param, $app_session;

    public $filterable, $for_cec;

    use WithPagination;

    public function mount($param)
    {
        $this->programme = 1;
        $this->option = "";
        $this->search_level = 1;
        $this->search_semester = SEMESTERS[1];
        $this->param = $param;
        $this->app_session = session()->get('app_session');
        $this->sch_session = session()->get('sch_session');
        $this->filterable = false;
        $this->for_cec = 0;
    }

    public function updated($field)
    {
        if ($field == 'app_session') {
            session()->put('app_session', $this->app_session);
        } elseif ($field == 'sch_session') {
            session()->put('sch_session', $this->sch_session);
        } elseif ($field == 'programme') {
            $this->option = "";
            $this->search_level = "";
        }

        $this->filterable = false;
    }

    public function filterParams()
    {
        $session_year = explode('/', $this->sch_session)[0];
        if ($this->app_session > $session_year)
            return session()->flash('error_toast', 'Invalid Set and Session supplied!');
        elseif (($this->app_session < $session_year && in_array($this->search_level, [1, 3]))
            || ($this->app_session == $session_year && in_array($this->search_level, [2, 4]))
        )
            return session()->flash('error_toast', 'Invalid Level supplied for Set and Session!');

        if ($this->option) {
            return $this->filterable = true;
        }

        return session()->flash('error_toast', 'Please select all filter parameters!');
    }

    public function render()
    {
        $deptOptions = DeptOption::where('dept_id', auth()->user()->department_id)->where('prog_id', $this->programme)->get();
        $courses = [];

        if ($this->filterable) {
            $courses = Course::whereRaw("for_set like '%$this->app_session%'");
            $courses->where('department_id', auth()->user()->department_id);
            // if ($this->search_semester) 
            $courses->whereRaw("semester = '$this->search_semester'");
            // if ($this->search_level) 
            $courses->whereRaw("levels = '$this->search_level'");
            // if ($this->option) 
            $courses->where('stdcourse', $this->option);
            $courses->where('for_cec', $this->for_cec);
            $courses = $courses->paginate(PAGINATE_SIZE * 2);
        }

        $levels = Level::whereProgrammeId($this->programme)->get(['level_id', 'level_name']);
        $programmes = Programme::all(['programme_id', 'programme_name']);
        $sessions = SchoolSession::all(['year', 'session']);
        return view('livewire.hod.view-courses-component', compact('courses', 'deptOptions', 'levels', 'programmes', 'sessions'));
    }
}
