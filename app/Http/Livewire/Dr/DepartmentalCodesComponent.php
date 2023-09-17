<?php

namespace App\Http\Livewire\Dr;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Matcode;
use App\Models\Programme;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DepartmentalCodesComponent extends Component
{
    public $fac_id, $dept_id, $prog_id;

    public $data = [], $is_cec;

    protected $rules = [
        'fac_id'    =>  'required',
        'dept_id'    =>  'nullable',
        'prog_id'    =>  'nullable',
    ];

    public function mount()
    {
        $user = auth()->user();
        $this->is_cec = $user->prog_type_id == 2 ? 1 : 0;
        if ($user->department_id) {
            $this->dept_id = $user->department_id;
            $this->fac_id = Department::select(['fac_id'])->find($user->department_id)->fac_id;
        }
    }

    public function updated($field)
    {
        if ($field == 'fac_id' || $field == 'dept_id' || $field == 'prog_id')
            if (count($this->data)) $this->data = [];
    }

    public function addData()
    {
        $this->validate();
        $dept_options = DB::table('dept_options')->selectRaw("
            do_id,
            departments.departments_name as dept_name,
            programme_option as option_name,
            dept_id, 
            prog_id
        ")->join('departments', 'departments.departments_id', 'dept_options.dept_id')
            ->where('departments.fac_id', $this->fac_id);
        if ($this->dept_id) $dept_options->whereDeptId($this->dept_id);
        if ($this->prog_id) $dept_options->whereProgId($this->prog_id);
        $dept_options = $dept_options->get();

        $data = [];
        $prog_types = $this->is_cec ? [2] : PROG_TYPES;
        foreach ($dept_options as $option) {
            foreach ($prog_types as $progtype_id => $progtype) {
                if ($progtype_id == 3 and $option->prog_id == 2) continue;
                $mid = 0;
                $deptcode = "";
                $matcode = UserService::matcode($option->do_id, $option->prog_id, $progtype_id)->first();
                if ($matcode) {
                    $mid = $matcode->mid;
                    $deptcode = $matcode->deptcode;
                }

                $data[$option->dept_id][] = [
                    'mid'   =>  $mid,
                    'deptcode'  =>  $deptcode,
                    'deptname'  =>  $option->dept_name,
                    'option_name'  =>  $option->option_name,
                    'do_id'     =>  $option->do_id,
                    'progtype_id'     =>  $progtype_id,
                    'prog_id'     =>  $option->prog_id,
                ];
            }
        }
        // dd($data);
        $this->data = $data;
    }


    public function submit()
    {
        if ($this->data) {
            foreach ($this->data as $dept_codes) {
                foreach ($dept_codes as $code) {
                    $data = $code;
                    unset($data['mid']);
                    unset($data['option_name']);
                    if ($code['mid']) {
                        Matcode::find($code['mid'])->update($data);
                    } else {
                        if ($code['deptcode'])
                            Matcode::create($data);
                    }
                }
            }
            $this->data = [];
            return session()->flash('success_toast', 'Department codes updated!');
        }
    }

    public function render()
    {
        $faculties = Faculty::all(['faculties_id', 'faculties_name']);
        $departments = [];
        if ($this->fac_id) $departments = Department::whereFacId($this->fac_id)->get(['departments_id', 'departments_name']);
        $programmes = Programme::all(['programme_id', 'programme_name']);

        return view(
            'livewire.dr.departmental-codes-component',
            compact('faculties', 'departments', 'programmes')
        );
    }
}
