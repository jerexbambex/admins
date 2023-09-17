<?php

namespace App\Http\Livewire\Hod;

use App\Models\Course;
use App\Models\DeptOption;
use App\Models\LecturerCourse;
use App\Models\Level;
use App\Models\Programme;
use App\Models\ProgrammeType;
use App\Models\SchoolSession;
use App\Models\StudentResult;
use App\Models\StudentResultsConfig;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class HodViewResultsComponent extends Component
{
    public $programme_type, $forcec, $level_id, $course_id;
    public $semester, $session, $app_session, $lec_course_id;
    public $editable_config, $editing_data, $is_bos_moderation;
    public $option_id, $programme;

    public $filterable = false;

    public function mount()
    {
        $this->semester = 'First Semester';
        $this->forcec = 0;
        $this->level_id = '';
        $this->programme_type = 1;
        $this->course_id = '';
        $this->option_id = '';
        $this->programme = '';
        $this->app_session = session()->get('app_session');
        $this->session = session()->get('sch_session');
        $this->lec_course_id = 0;
        $this->editable_config = false;
        $this->is_bos_moderation = false;
        $this->get_editable_config();
        $this->resetEditableConfig();
    }

    public function resetEditableConfig()
    {
        $this->editing_data = [
            'id'    =>  null,
            'c_a'   =>  0,
            'mid_sem'   =>  0,
            'exam'   =>  0
        ];
    }

    public function updated($field)
    {
        if ($field == 'app_session') {
            $this->course_id = "";
            $this->lec_course_id = 0;
            $this->get_editable_config();
        }

        if ($field == 'programme') {
            $this->option_id = "";
            $this->level_id = '';
            $this->course_id = '';
            $this->lec_course_id = 0;
        } elseif (in_array($field, ['option_id', 'set', 'level_id'])) {
            $this->course_id = '';
            $this->lec_course_id = 0;
        } elseif ($field == 'course_id') {
            if ($lecturerCourse =
                LecturerCourse::select('id')->whereCourseId($this->course_id)->whereProgrammeTypeId($this->programme_type)->first()
            ) {
                $this->lec_course_id = $lecturerCourse->id;
            }
        }

        $this->filterable = false;
    }

    public function get_editable_config()
    {
        $editable = false;
        $configs = StudentResultsConfig::select([
            'departmental_moderation_start_date', 'departmental_moderation_end_date',
            // 'bos_moderation_start_date', 'bos_moderation_end_date'
        ])
            ->firstWhere('session_year', explode('/', $this->session)[0]);

        if ($configs) {
            $today = Carbon::now();
            if ($configs->departmental_moderation_start_date && $configs->departmental_moderation_end_date) {
                $start = Carbon::parse($configs->departmental_moderation_start_date);
                $end = Carbon::parse($configs->departmental_moderation_end_date);

                if ($today->gte($start) && $today->lte($end)) $editable = true;
            }
            // if ($configs->bos_moderation_start_date && $configs->bos_moderation_end_date) {
            //     $start = Carbon::parse($configs->bos_moderation_start_date);
            //     $end = Carbon::parse($configs->bos_moderation_end_date);

            //     if ($today->gte($start) && $today->lte($end)) $this->is_bos_moderation = true;
            // }
        }
        $this->editable_config = $editable;
    }

    public function getResult()
    {
        $results = StudentResult::where('prog_type_id', $this->programme_type);
        $results->join('courses', 'courses.thecourse_id', 'student_results.course_id');
        $results->whereRaw("courses.for_set like '%$this->app_session%'");
        $results->whereSession($this->session);
        if ($this->level_id) $results = $results->where('level_id', $this->level_id);
        if ($this->course_id) $results = $results->where('course_id', $this->course_id);
        return $results->orderByRaw("matric_number asc");
    }

    // public function resetResultUpload()
    // {
    //     $results = StudentResult::where('prog_type_id', $this->programme_type);
    //     $results->whereSession($this->session);
    //     if ($this->level_id) $results = $results->where('level_id', $this->level_id);
    //     if ($this->course_id) $results = $results->where('course_id', $this->course_id);
    //     if ($results->delete())
    //         return session()->flash('success_toast', 'Result upload reset successful!');

    //     return session()->flash('error_toast', 'Unable to perform that action!');
    // }

    public function enableEdit(StudentResult $studentResult)
    {
        if ($studentResult) {
            $this->editing_data = [
                'id'    =>  $studentResult->id,
                'c_a'   =>  $studentResult->c_a,
                'mid_sem'   =>  $studentResult->mid_semester,
                'exam'   =>  $studentResult->examination
            ];
            return true;
        }
        return session()->flash('error_toast', 'Unable to perform that action!');
    }

    public function submitEditedResult(StudentResult $studentResult)
    {
        if ($studentResult && $studentResult->id == $this->editing_data['id']) {
            $c_a = (float) $this->editing_data['c_a'];
            $mid_semester = (float) $this->editing_data['mid_sem'];
            $examination = (float) $this->editing_data['exam'];
            $total = $c_a + $mid_semester + $examination;

            if ($total > 100 || $c_a < 0 || $mid_semester < 0 || $examination < 0)
                return session()->flash('error_toast', 'Miscalculation detected!');

            $grade = grade($total);

            if ($studentResult->update(compact('c_a', 'mid_semester', 'examination', 'total', 'grade'))) {
                $this->resetEditableConfig();
                return session()->flash('success_toast', 'Successfully Updated!');
            }
        }
        return session()->flash('error_toast', 'Unable to perform that action!');
    }

    public function filterParams()
    {
        if ($this->option_id && $this->course_id)
            $this->filterable = true;
    }

    use WithPagination;

    public function render()
    {
        $this->forcec = $this->programme_type == 2 ? 1 : 0;

        $courses = $results = $levels = $dept_options = [];

        if ($this->level_id && $this->semester && $this->session) {
            $courses = Course::whereDepartmentId(auth()->user()->department_id);
            $courses = $courses->where('levels', $this->level_id);
            $courses = $courses->where('for_cec', $this->forcec);
            $courses = $courses->where('semester', $this->semester);
            $courses = $courses->where('stdcourse', $this->option_id);
            $courses = $courses->whereRaw("for_set like '%$this->app_session%'");
            $courses = $courses->get();

            if ($this->filterable) {
                $results = $this->getResult()->paginate(PAGINATE_SIZE);
            }
        }

        $progtypes = ProgrammeType::all(['programmet_id', 'programmet_name']);
        $programmes = Programme::all(['programme_id', 'programme_name']);
        $sessions = SchoolSession::orderByDesc('year')->get(['year', 'session']);

        if ($this->programme) {
            $dept_options = DeptOption::whereDeptId(auth()->user()->department_id)->whereProgId($this->programme)->get(['do_id', 'programme_option']);
            $levels = Level::whereProgrammeId($this->programme)->get(['level_id', 'level_name']);
        }

        return view('livewire.hod.hod-view-results-component', compact('results', 'progtypes', 'courses', 'levels', 'sessions', 'programmes', 'dept_options'));
    }
}
