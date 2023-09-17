<?php

namespace App\Http\Livewire\Admission;

use App\Models\SchoolSession;
use App\Models\Student;
use App\Services\AdmissionsService;
use Livewire\Component;
use Livewire\WithPagination;

class StudentComponent extends Component
{
    public $fac_id, $department_id = 0, $do_id, $prog_id = 0, $prog_type_id = 0;

    public $pagesize = 10, $clear_status = 0;

    public $search = '', $adm_year;

    public $date_from, $date_to;

    use WithPagination;

    public function mount($fac_id, $dept_id, $do_id)
    {
        $this->fac_id = $fac_id;
        $this->department_id = $dept_id;
        $this->do_id = $do_id;
        $this->search = '';
        $this->pagesize = 10;
        $this->clear_status = 0;
        $this->prog_type_id = auth()->user()->prog_type_id;
        $this->adm_year = SchoolSession::latest()->first()->year;
    }

    public function clear(Student $student)
    {
        try {
            $student->eclearance = 1;
            $student->date_cleared = now();
            if ($student->save()) return session()->flash('success_toast', 'Student has been cleared');
            return session()->flash('error_toast', 'Unable to perform that action!');
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'Unable to perform that action!');
        }
    }

    public function unClear(Student $student)
    {
        try {
            $student->eclearance = 0;
            if ($student->save()) return session()->flash('success_toast', 'Clearance has been reverted');
            return session()->flash('error_toast', 'Unable to perform that action!');
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'Unable to perform that action!');
        }
    }

    public function download()
    {
        return redirect()->route(
            'download_students',
            [
                'adm_year' => $this->adm_year,
                'prog_id' => $this->prog_id,
                'prog_type_id' => $this->prog_type_id,
                'do_id' => $this->do_id,
                'department_id' => $this->department_id,
                'fac_id' => $this->fac_id,
                'clear_status' => $this->clear_status,
                'search' => $this->search,
                'date_from' => $this->date_from,
                'date_to' => $this->date_to
            ]
        );
    }

    public function render()
    {

        $today_array = [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')];
        $adm_year = $this->adm_year;
        $cleared_query = Student::select('std_id')->whereStdAdmyear($adm_year)->whereEclearance(1);
        if ($this->prog_type_id) $cleared_query = $cleared_query->where('stdprogrammetype_id', $this->prog_type_id);
        $uncleared_query = Student::select('std_id')->whereStdAdmyear($adm_year)->whereEclearance(0);
        if ($this->prog_type_id) $uncleared_query = $uncleared_query->where('stdprogrammetype_id', $this->prog_type_id);

        if ($this->do_id) {
            $cleared_query = $cleared_query->whereStdcourse($this->do_id);
            $uncleared_query = $uncleared_query->whereStdcourse($this->do_id);
        } elseif ($this->department_id) {
            $cleared_query = $cleared_query->whereStddepartmentId($this->department_id);
            $uncleared_query = $uncleared_query->whereStddepartmentId($this->department_id);
        } elseif ($this->fac_id) {
            $cleared_query = $cleared_query->whereStdfacultyId($this->fac_id);
            $uncleared_query = $uncleared_query->whereStdfacultyId($this->fac_id);
        }


        $uncleared = $uncleared_query->count();
        $total_cleared = $cleared_query->count();
        $cleared_today = $cleared_query->whereBetween('date_cleared', $today_array)->count();


        $students = AdmissionsService::getStudents(
            $this->adm_year,
            $this->prog_id,
            $this->prog_type_id,
            $this->do_id,
            $this->department_id,
            $this->fac_id,
            $this->clear_status,
            $this->search,
            $this->date_from,
            $this->date_to
        )->paginate($this->pagesize);

        $sch_sessions = SchoolSession::orderByDesc('year')->get(['year', 'session']);
        return view('livewire.admission.student-component', compact('students', 'total_cleared', 'uncleared', 'cleared_today', 'sch_sessions'));
    }
}
