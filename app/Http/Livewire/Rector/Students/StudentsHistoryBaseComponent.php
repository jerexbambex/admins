<?php

namespace App\Http\Livewire\Rector\Students;

use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Faculty;
use App\Models\Level;
use App\Models\SchoolSession;
use App\Services\StudentService;
use Livewire\Component;
use Livewire\WithPagination;

class StudentsHistoryBaseComponent extends Component
{
    public $faculty_id = 0, $department_id = 0,
        $level_id = 1, $prog_id = 1, $prog_type_id = 0,
        $adm_year = '2020', $opt_id = 0;

    public $param, $search_param;

    public function mount($param)
    {
        $this->param = $param;
    }

    public function download()
    {
        return redirect()->route('downloadStudentsDataReport', [
            'fac_id' => $this->faculty_id,
            'dept_id' => $this->department_id,
            'opt_id' => $this->opt_id,
            'level_id' => $this->level_id,
            'sess_id' => $this->adm_year,
            'prog_id' => $this->prog_id,
            'progtype_id' => $this->prog_type_id,
            'param' => $this->param,
            'search_param' => base64_encode($this->search_param)
        ]);
    }

    use WithPagination;

    public function render()
    {
        $faculties = Faculty::all(['faculties_id', 'faculties_name']);
        $departments = $options = $levels = [];
        if ($this->faculty_id) $departments = Department::whereFacId($this->faculty_id)->get(['departments_id', 'departments_name']);
        if ($this->department_id && $this->prog_id)
            $options = DeptOption::whereProgId($this->prog_id)->whereDeptId($this->department_id)->get(['do_id', 'programme_option']);

        if ($this->prog_id) $levels = Level::where('programme_id', $this->prog_id)->get(['level_id', 'level_name']);

        $sch_sessions = SchoolSession::all(['year', 'session']);

        $students = StudentService::studentsQuery(
            $this->faculty_id,
            $this->department_id,
            $this->opt_id,
            $this->level_id,
            $this->adm_year,
            $this->prog_id,
            $this->prog_type_id,
            $this->search_param
        )->paginate(PAGINATE_SIZE);

        return view(
            'livewire.rector.students.students-history-base-component',
            compact(
                'faculties',
                'departments',
                'options',
                'sch_sessions',
                'levels',
                'students'
            )
        );
    }
}
