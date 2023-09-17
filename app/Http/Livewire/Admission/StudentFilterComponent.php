<?php

namespace App\Http\Livewire\Admission;

use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Faculty;
use App\Models\SchoolSession;
use App\Models\Student;
use Livewire\Component;

class StudentFilterComponent extends Component
{
    public $fac_id, $dept_id, $do_id;

    public function mount()
    {
        $this->fac_id = 0;
        $this->dept_id = 0;
        $this->do_id = 0;
    }

    public function goBack()
    {
        if($this->do_id) $this->do_id = 0;
        elseif($this->dept_id) $this->dept_id = 0;
        elseif($this->fac_id) $this->fac_id = 0;
    }

    public function resetForm()
    {
        $this->mount();
    }

    public function render()
    {
        $data = [];
        $today_array = [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')];
        $adm_year = SchoolSession::latest()->first()->year;
        // $prog_type_id = auth()->user()->prog_type_id;
        $title = "";
        if(!$this->fac_id) {
            $data = Faculty::all();
        }
        elseif(!$this->dept_id){
            $title = "Faculty: ".Faculty::find($this->fac_id)->faculties_name;
            $data = Department::whereFacId($this->fac_id)->get();
        } 
        elseif(!$this->do_id){
            $title = "Department: ".Department::find($this->dept_id)->departments_name;
            $data = DeptOption::whereDeptId($this->dept_id)->get();
        }

        $uncleared = 0;
        $total_cleared = 0;
        $cleared_today = 0;
        
        return view('livewire.admission.student-filter-component', compact('data', 'total_cleared', 'uncleared', 'cleared_today', 'title'));
    }
}
