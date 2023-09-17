<?php

namespace App\Http\Livewire;

use App\Models\SchoolSession;
use App\Models\StudentResultsConfig;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class SchoolDatesComponent extends Component
{
    public $sch_session, $semester, $programme_type, $date_type;
    public $start_date, $end_date;

    public $action;

    public $app_session, $filter_progtype, $filter_semester,
        $filterable, $filter_date_type;

    protected $listeners = ['trigger_update' => 'enable_update'];

    protected $rules = [
        'sch_session' => 'required',
        'semester' => 'required',
        'programme_type' => 'required',
        'start_date' => 'required',
        'end_date' => 'required',
        'date_type' =>  'required'
    ];

    public function mount()
    {
        $this->sch_session = session()->get('app_session');
        $this->app_session = session()->get('app_session');
        $this->semester = 1;
        $this->date_type = 'student_registration';
        $this->action = 'add';
        $this->programme_type = "0";
        $this->start_date = null;
        $this->end_date = null;

        $this->filter_progtype = '0';
        $this->filter_semester = 'all';
        $this->filterable = true;
        $this->filter_date_type = 'all';
    }

    public function updated($field)
    {
        if (in_array($field, ['filter_progtype', 'filter_semester', 'app_session', 'filter_date_type']))
            $this->filterable = false;
    }

    public function enable_update(StudentResultsConfig $studentResultsConfig, $date_type)
    {
        if ($studentResultsConfig) {
            $config_arr = $studentResultsConfig->toArray();
            $this->sch_session = $studentResultsConfig->session_year;
            $this->date_type = $date_type;
            $this->semester = $studentResultsConfig->semester_id;
            $this->programme_type = $studentResultsConfig->for_cec;
            $this->start_date = $config_arr[$date_type . '_start_date'];
            $this->end_date = $config_arr[$date_type . '_end_date'];
            $this->action = 'update';
            session()->flash('success_toast', "Update function enabled!");
        }
    }

    public function submit()
    {
        $this->validate();
        if (!in_array($this->action, ['add', 'update']))
            return session()->flash('error_toast', "Unable to perform that action, bad action!");

        $start_date = Carbon::parse($this->start_date);
        $end_date = Carbon::parse($this->end_date);

        if ($start_date->gte($end_date))
            return session()->flash('error_toast', "Invalid date format selected!");

        $config = StudentResultsConfig::whereSessionYear($this->sch_session)->whereSemesterId($this->semester)
            ->where(function ($query) {
                $query->whereForCec($this->programme_type)->orWhere('for_cec', 2);
            })->first();

        if ($config) {
            $config_arr = $config->toArray();

            $data = [];

            if ($this->action == 'add') {
                if ($config_arr[$this->date_type . '_start_date'] || $config_arr[$this->date_type . '_end_date'])
                    return session()->flash('error_toast', "Unable to perform that action, already exist!");

                $data = [
                    $this->date_type . '_start_date'  =>  $start_date,
                    $this->date_type . '_end_date'  =>  $end_date
                ];
            } elseif ($this->action == 'update') {
                if (!$config_arr[$this->date_type . '_start_date'] || !$config_arr[$this->date_type . '_end_date'])
                    return session()->flash('error_toast', "Unable to perform that action, not in existence!");

                $start_date = Carbon::parse($config_arr[$this->date_type . '_start_date']);
                if ($start_date->gte($end_date))
                    return session()->flash('error_toast', "Invalid date format selected!");

                $data = [
                    $this->date_type . '_end_date'  =>  $end_date
                ];
            }


            if ($data) {
                $config->update($data);
                $this->mount();
                return session()->flash('success_alert', "Submitted successfully!");
            }
        } elseif (!$config && $this->action == 'update') {
            return session()->flash('error_toast', "Unable to perform that action, not in existence!");
        } elseif ($this->action == 'add') {
            StudentResultsConfig::create([
                'session_year'  =>  $this->sch_session,
                'semester_id'   =>  $this->semester,
                $this->date_type . '_start_date'  =>  $start_date,
                $this->date_type . '_end_date'  =>  $end_date,
                'for_cec'   =>  "$this->programme_type"
            ]);
            $this->mount();
            return session()->flash('success_alert', "Submitted successfully!");
        }
        return session()->flash('error_toast', "Unable to perform that action, bad action!");
    }

    public function render()
    {
        $configs = [];
        $sessions = SchoolSession::all(['year', 'session']);
        $date_types = [
            'tuition_payments', 'student_registration',
            'course_update_fee', 'late_registration_fee',
            'lecturer_upload', 'departmental_moderation',
            'bos_moderation', 'student_results_enable'
        ];

        if ($this->filterable) {
            $configs = StudentResultsConfig::whereSessionYear($this->app_session)
                ->where(function ($query) {
                    $query->whereForCec($this->filter_progtype)->orWhere('for_cec', 2);
                });
            if ($this->filter_semester <> 'all') $configs->whereSemesterId($this->filter_semester);
            $configs = $configs->get();
        }
        return view('livewire.school-dates-component', compact('sessions', 'date_types', 'configs'));
    }
}
