<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Faculty;
use App\Models\GraduatingRequirement;
use App\Models\Programme;
use App\Models\SchoolSession;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class GraduatingRequirementsComponent extends Component
{
    public $faculty_id, $department_id, $dept_option_id, $programme_id;
    public $core, $elective, $gs;
    public $admission_year;

    public $is_update = false, $is_hod = false;
    public $update_id = 0;

    protected function rules()
    {
        $rules = [
            'core'              =>  'required|numeric|min:0',
            'elective'          =>  'required|numeric|min:0',
            'gs'                =>  'required|numeric|min:0'
        ];

        if (!$this->is_update) {
            $rules = array_merge($rules, [
                'faculty_id'        =>  'required',
                'department_id'     =>  'required',
                'dept_option_id'    =>  'required',
                'programme_id'      =>  'required',
                'admission_year'    =>  'required'
            ]);
        }

        return $rules;
    }

    protected $messages = [
        'faculty_id.required'        =>  'Please select a faculty',
        'department_id.required'     =>  'Please select a department',
        'dept_option_id.required'    =>  'Please select a dept. option',
        'programme_id.required'      =>  'Please select a programme'
    ];

    function mount()
    {
        $this->faculty_id = $this->department_id = $this->dept_option_id = $this->programme_id = '';
        $this->admission_year = session()->get('app_session');

        $this->resetUpdatable();

        $this->is_hod = User::find(auth()->id())->hasRole('hod');

        Cache::forget('departments');
        Cache::forget('dept_options');

        if ($this->is_hod) {
            $this->department_id = auth()->user()->department_id;
            $this->faculty_id = auth()->user()->faculty_id;
        }
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
        } elseif ($field === 'admission_year') {
            session()->put('app_session', $this->admission_year);
        }

        $this->validateOnly($field);
    }

    function resetUpdatable()
    {
        $this->update_id = 0;
        $this->core = $this->elective = $this->gs = 0;
        $this->is_update = false;

        $this->resetForm();
    }

    function resetForm()
    {
        $this->core = $this->elective = $this->gs = 0;
    }

    function setUpdatable(GraduatingRequirement $graduatingRequirement)
    {
        $this->update_id = $graduatingRequirement->id;
        $this->core = $graduatingRequirement->core;
        $this->elective = $graduatingRequirement->elective;
        $this->gs = $graduatingRequirement->gs;
        $this->is_update = true;
    }

    function submit()
    {
        $this->validate();
        try {
            $data = [
                'core'      =>  $this->core,
                'elective'  =>  $this->elective,
                'gs'        =>  $this->gs
            ];

            if ($this->is_update) {
                if ($this->update_id) {
                    if (GraduatingRequirement::whereId($this->update_id)->update($data)) {
                        session()->flash('success_alert', 'Update successful!');
                        return $this->resetUpdatable();
                    }
                }
            } else {
                $extra_data = [
                    'admission_year'    =>  $this->admission_year,
                    'dept_option_id'    =>  $this->dept_option_id
                ];
                if (GraduatingRequirement::where($extra_data)->count()) {
                    return session()->flash('error_toast', 'Graduating requirement already exist for that set.');
                }
                if (GraduatingRequirement::create(array_merge($extra_data, $data))) {
                    session()->flash('success_alert', 'Requirement added successfully!');
                    return $this->resetForm();
                }
            }

            return session()->flash('error_toast', 'Unable to handle that request.');
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'An error occured.');
        }
    }

    use WithPagination;

    public function render()
    {
        $school_sessions = Cache::remember('admission_years', 3600, function () {
            return SchoolSession::all(['year']);
        });

        $faculties = $departments = [];
        if (!$this->is_hod) {
            $faculties = Cache::rememberForever('faculties', function () {
                return Faculty::all(['faculties_id', 'faculties_name']);
            });

            $departments = Cache::remember('departments', 3600, function () {
                return Department::whereFacId($this->faculty_id)->get(['departments_id', 'departments_name']);
            });
        }

        // $dept_options = Cache::remember('dept_options', 3600, function () {
        $dept_options = DeptOption::whereDeptId($this->department_id)->whereProgId($this->programme_id)->get(['do_id', 'programme_option', 'prog_id']);
        // });

        $programmes = Cache::rememberForever('programmes', function () {
            return Programme::all(['programme_name', 'programme_id']);
        });

        $graduating_requirements = GraduatingRequirement::select(['*']);
        if ($this->admission_year) $graduating_requirements->whereAdmissionYear($this->admission_year);
        if ($this->department_id) $graduating_requirements->whereRaw("dept_option_id in (SELECT do_id FROM dept_options where dept_id = $this->department_id)");
        if ($this->dept_option_id) $graduating_requirements->whereDeptOptionId($this->dept_option_id);
        $graduating_requirements = $graduating_requirements->paginate(PAGINATE_SIZE);

        return view(
            'livewire.graduating-requirements-component',
            compact(
                'school_sessions',
                'faculties',
                'departments',
                'programmes',
                'dept_options',
                'graduating_requirements'
            )
        );
    }
}
