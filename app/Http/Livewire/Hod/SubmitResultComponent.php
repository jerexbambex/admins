<?php

namespace App\Http\Livewire\Hod;

use App\Models\BosLog;
use App\Models\DeptOption;
use App\Models\Level;
use App\Models\Programme;
use App\Models\ProgrammeType;
use App\Models\SchoolSession;
use App\Models\StudentResult;
use App\Models\StudentResultsConfig;
use App\Services\ResultService;
use Carbon\Carbon;
use Livewire\Component;

class SubmitResultComponent extends Component
{
    public $programme_type, $level_id;
    public $semester, $session, $app_session;
    public $option_id, $programme, $bos_number;

    protected $rules = [
        'programme_type'    =>  'required',
        'level_id'    =>  'required',
        'semester'    =>  'required',
        'session'    =>  'required',
        'app_session'    =>  'required',
        'option_id'    =>  'required',
        'programme'    =>  'required',
        'bos_number'    =>  'required'
    ];

    protected $messages = [
        'level_id.required' => 'The level field is required',
        'app_session.required' => 'The set field is required',
        'option_id.required' => 'The option field is required'
    ];

    public function mount()
    {
        $this->semester = 'First Semester';
        $this->programme_type = 1;
        $this->level_id = $this->option_id = $this->programme = $this->bos_number = '';
        $this->app_session = session()->get('app_session');
        $this->session = session()->get('sch_session');
    }

    public function updated($field)
    {
        if ($field == 'programme') {
            $this->option_id = "";
            $this->level_id = '';
        }
    }

    public function can_upload()
    {
        $configs = StudentResultsConfig::select([
            'departmental_moderation_start_date', 'departmental_moderation_end_date'
        ])
            ->firstWhere('session_year', explode('/', $this->session)[0]);

        if (!$configs) return false;

        $today = Carbon::now();
        if ($configs->departmental_moderation_start_date && $configs->departmental_moderation_end_date) {
            $start = Carbon::parse($configs->departmental_moderation_start_date);
            $end = Carbon::parse($configs->departmental_moderation_end_date);

            if ($today->gte($start) && $today->lte($end)) return true;
        }

        return false;
    }

    function submit()
    {
        $this->validate();
        if (!$this->can_upload()) {
            return session()->flash('error_toast', 'Result submission has been disabled!');
        }

        try {

            $semester_id = SEMESTER_KEYS[$this->semester];

            // get filter params
            $filter = [
                'session' => $this->session,
                'semester' => $semester_id,
                'level_id' => $this->level_id,
                'prog_type_id' => $this->programme_type,
            ];

            // get current presentation
            $presentation = ResultService::getPresentation(
                $this->option_id,
                $this->app_session,
                ...array_values($filter)
            );

            // check if bos number already exist/used
            if ($bosLog = BosLog::whereBosNumber($this->bos_number)->first()) {
                if ($bosLog->option_id !== $this->option_id) {
                    return session()->flash('error_toast', 'That BOS number already exist for another department/option');
                }
                if ($presentation === 1) {
                    return session()->flash('error_toast', 'That BOS number already used');
                }
            }

            // get students' list
            $students_data = ResultService::getStudents(
                $this->session,
                $this->app_session,
                $semester_id,
                $this->level_id,
                $this->programme,
                $this->programme_type,
                auth()->user()->department_id,
                $this->option_id
            );

            /**
             * Get students' log_id (has course registration, not has awaiting and is active)
             * @var array<int, int>
             */
            $students_list = [];

            foreach ($students_data as $student) {
                if (
                    $student->hasCourseReg($this->session, $semester_id, $this->level_id) &&
                    $student->status == 'active' &&
                    !$student->hasAwaiting($this->session, $semester_id, $this->level_id)
                ) {
                    $students_list[] = $student->std_logid;
                }
            }

            if ($students_list) {
                // Update student results table parameters
                StudentResult::where($filter)->whereIn('log_id', $students_list)
                    ->update([
                        'presentation'  =>  $presentation,
                        'bos_number'    =>  $this->bos_number,
                        'lecturer_editable' => 0,
                        'hod_editable'  => 0
                    ]);

                // If bos number against presentation exists on student results
                if (StudentResult::wherePresentation($presentation)->whereBosNumber($this->bos_number)->count()) {

                    // If bos number against presentation does not exist
                    if (BosLog::where([
                        'bos_number' => $this->bos_number,
                        'presentation' => $presentation,
                    ])->count() === 0) {
                        // Create Bos Logs
                        BosLog::create([
                            'option_id' => $this->option_id,
                            'adm_year' => $this->app_session,
                            'session' => $this->session,
                            'level_id' => $this->level_id,
                            'semester_id' => $semester_id,
                            'prog_id' => $this->programme,
                            'prog_type_id' => $this->programme_type,
                            'bos_number' => $this->bos_number,
                            'presentation' => $presentation,
                        ]);
                    }
                }
            }

            $this->mount();
            return session()->flash('success_toast', 'Result submitted!');
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'An error occured!');
        }
    }

    public function render()
    {
        $levels = $dept_options = [];

        $progtypes = ProgrammeType::all(['programmet_id', 'programmet_name']);
        $programmes = Programme::all(['programme_id', 'programme_name']);
        $sessions = SchoolSession::orderByDesc('year')->get(['year', 'session']);

        if ($this->programme) {
            $dept_options = DeptOption::whereDeptId(auth()->user()->department_id)->whereProgId($this->programme)->get(['do_id', 'programme_option']);
            $levels = Level::whereProgrammeId($this->programme)->get(['level_id', 'level_name']);
        }

        return view(
            'livewire.hod.submit-result-component',
            compact(
                'progtypes',
                'levels',
                'sessions',
                'programmes',
                'dept_options'
            )
        );
    }
}
