<?php

namespace App\Http\Livewire\Student;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Portal;
use App\Models\SchoolSession;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LostAccessComponent extends Component
{
    public $appno, $fullname, $gender, $dcos, $school, $state, $prog, $progtype, $level, $stdtype, $adm_year;
    public $is_valid;

    protected $rules = [
        'appno' =>  'required',
        'fullname'  =>  'required',
        'gender'    =>  'required',
        'dcos'  =>  'required',
        'school'    =>  'required',
        'state' =>  'required',
        'prog'  =>  'required',
        'progtype' =>  'required',
        'stdtype'   =>  'required',
        'adm_year'  =>  'required',
    ];

    public function mount()
    {
        $this->appno = '';
        $this->fullname = '';
        $this->gender = '';
        $this->dcos = '';
        $this->school = '';
        $this->state = '';
        $this->prog = '';
        $this->progtype = '';
        $this->level = '';
        $this->stdtype = 'new';
        $this->adm_year = '';
        $this->is_valid = false;
    }

    public function validateFormNumber()
    {
        if(!$this->appno) return session()->flash('error_toast', 'Please enter form number');
        if(!Portal::whereAppno($this->appno)->count()) $this->is_valid = true;
    }

    public function getAccess()
    {
        if(!$this->is_valid) return session()->flash('error_toast', 'Form number not valid');

        $data = $this->validate();
        $data['level'] = $this->prog == 1 ? 1 : 3;
        $data['date_admitted'] = Carbon::now();

        if(DB::table('portalaccess')->insert($data)){
            session()->flash('success_toast', 'Access granted!');
            return $this->mount();
        }else session()->flash('error_toast', 'Unable to perform that action!');
    }

    public function render()
    {
        $states = State::all();
        $progs = PROGRAMMES;
        $prog_types = PROG_TYPES;
        $faculties = Faculty::all();
        $departments = Department::where('fac_id', $this->school)->get();
        $sessions = SchoolSession::latest()->get();
        return view('livewire.student.lost-access-component', compact('states', 'progs', 'prog_types', 'faculties', 'departments', 'sessions'));
    }
}
