<?php

namespace App\Http\Livewire\Hod;

use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Level;
use App\Models\SchoolSession;
use Livewire\Component;

class DownloadResultComponent extends Component
{
    public $sch_session, $semester, $level, $prog_type,
        $prog_id, $option_id, $set_year;

    public function mount()
    {
        $this->set_year = session()->get('app_session');
        $this->sch_session = session()->get('sch_session');
        $this->semester = 1;
        $this->level = 1;
        $this->prog_type = 1;
        $this->prog_id = 1;
        $this->option_id = "";
    }

    public function updated($field)
    {
        if ($field == 'prog_id') {
            $this->level = $this->prog_id == 1 ? 1 : 3;
            $this->option_id = "";
        } elseif ($field != 'option_id') {
            if ($this->option_id) $this->option_id = '';
        }
    }

    public function render()
    {
        $sessions = SchoolSession::all(['year', 'session']);
        $levels = $options = [];
        if ($this->prog_id) {
            $options = DeptOption::whereDeptId(auth()->user()->department_id)->whereProgId($this->prog_id)->get(['do_id', 'programme_option']);
            $levels = Level::where('programme_id', $this->prog_id)->get(['level_id', 'level_name']);
        }

        return view('livewire.hod.download-result-component', compact('sessions', 'levels', 'options'));
    }
}
