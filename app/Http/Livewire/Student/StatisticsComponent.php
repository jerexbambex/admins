<?php

namespace App\Http\Livewire\Student;

use App\Exports\StudentsStatisticsExport;
use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Faculty;
use App\Models\Level;
use App\Models\Programme;
use App\Models\ProgrammeType;
use App\Models\SchoolSession;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class StatisticsComponent extends Component
{
    public $fac_id, $dept_id, $opt_id, $sess_id, $level_id, $prog_id, $progtype_id, $semester_id;
    public $user_role = '';
    public $statistics_type = 'payment';

    public function mount()
    {
        $user = User::find(auth()->user()->id);
        $this->user_role = $user->user_role();
        $this->fac_id = $this->dept_id = $this->opt_id = $this->level_id = $this->prog_id = $this->progtype_id = 0;
        $this->progtype_id = $user->prog_type_id;
        $this->semester_id = 1;
        $this->sess_id = SchoolSession::select(['year'])->orderBy('year', 'desc')->first()->year;

        if ($this->user_role == 'hod') {
            $dept = Department::select(['departments_id', 'fac_id'])->find($user->department_id);
            $this->dept_id = $dept->departments_id;
            $this->fac_id = $dept->fac_id;
        }

        // if (auth()->user()) {
        //     if (session()->has('download_next') && session()->get('download_next')) {
        //         $params = session()->get('download_params');
        //         redirect()->route('registered-students-by-faculty', [
        //             'fac_id' => $params[0], 'level_id' => $params[1],
        //             'prog_id' => $params[3], 'progtype_id' => $params[4],
        //             'semester_id'   =>  $params[5], 'sess_id' => $params[2],
        //             'dept_id' => $params[6]
        //         ]);
        //     }
        // }
    }

    public function updated($field)
    {
        if ($field == 'fac_id') {
            $this->dept_id = 0;
            $this->opt_id = 0;
        }
        if ($field == 'prog_id') $this->level_id = 0;
    }

    public function downloadStats()
    {
        return redirect()->route('download-student-statistics', [
            'statistics_type' => $this->statistics_type,
            'fac_id' => $this->fac_id,
            'dept_id' => $this->dept_id,
            'prog_id' => $this->prog_id,
            'opt_id' => $this->opt_id,
            'sess_id' => $this->sess_id,
            'semester_id' => $this->semester_id
        ]);
    }

    use WithPagination;

    public function render()
    {
        $faculties = Faculty::all(['faculties_id', 'faculties_name']);
        $departments = $options = $levels = [];
        $programmes = Programme::all(['programme_id', 'programme_name']);
        $programme_types = ProgrammeType::all(['programmet_id', 'programmet_name']);
        $semesters = SEMESTERS;
        $sessions = SchoolSession::select(['year', 'session'])->orderBy('year', 'desc')->get();

        if ($this->fac_id) $departments = Department::whereFacId($this->fac_id)->get(['departments_id', 'departments_name']);
        if ($this->prog_id) $levels = Level::whereProgrammeId($this->prog_id)->get(['level_id', 'level_name']);
        if ($this->dept_id && $this->prog_id) //then
            $options = DeptOption::select(['do_id', 'programme_option'])->whereDeptId($this->dept_id)->whereProgId($this->prog_id)->get();

        $stats = \App\Services\StudentService::StudentsStatistics(
            $this->statistics_type,
            $this->fac_id,
            $this->dept_id,
            $this->prog_id,
            $this->opt_id,
            $this->sess_id,
            $this->semester_id
        )->paginate(20);

        return view(
            'livewire.student.statistics-component',
            compact(
                'faculties',
                'departments',
                'programmes',
                'programme_types',
                'levels',
                'options',
                'semesters',
                'sessions',
                'stats'
            )
        );
    }
}
