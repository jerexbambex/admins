<?php

namespace App\Http\Livewire\Lecturer;

use App\Models\LecturerCourse;
use App\Models\SchoolSession;
use App\Models\StudentResult;
use App\Models\StudentResultsConfig;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class LecturerViewResultsComponent extends Component
{
    public $search_param, $programme_type = 0, $lec_course_id;
    public $app_session, $session;
    public $editable_config;
    public $editing_data;

    public function mount()
    {
        $this->app_session = session()->get('app_session');
        $this->session = session()->get('sch_session');
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
        if ($field == 'session') {
            $this->app_session = explode('/', $this->session)[0];
            $this->lec_course_id = "";
            $this->get_editable_config();
        }
    }

    public function get_editable_config()
    {
        $editable = false;
        $configs = StudentResultsConfig::select(['lecturer_upload_start_date', 'lecturer_upload_end_date'])
            ->firstWhere('session_year', $this->app_session);

        if ($configs) {
            if($configs->lecturer_upload_start_date && $configs->lecturer_upload_end_date){
                $start = Carbon::parse($configs->lecturer_upload_start_date);
                $end = Carbon::parse($configs->lecturer_upload_end_date);
    
                $today = Carbon::now();
    
                if ($today->gte($start) && $today->lte($end)) $editable = true;
            }
        }
        $this->editable_config = $editable;
    }

    public function download()
    {
        return;
    }

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

    public function resetResultUpload()
    {
        if ($this->editable_config && $this->lec_course_id) {
            $lec_course = LecturerCourse::find($this->lec_course_id);
            if ($lec_course) {
                $results = StudentResult::where('prog_type_id', $lec_course->programme_type_id);
                $results->whereSession($this->session)->where('level_id', $lec_course->course->levels);
                $results->where('course_id', $lec_course->course_id);
                if ($results->delete())
                    return session()->flash('success_toast', 'Result upload reset successful!');
            }
        }
        return session()->flash('error_toast', 'Unable to perform that action!');
    }

    use WithPagination;

    public function render()
    {
        $results = $lec_course = [];

        if ($this->lec_course_id && $this->session) {
            $lec_course = LecturerCourse::with(['lecturer', 'course', 'programmeType'])->where('lecturer_id', auth()->user()->id)->find($this->lec_course_id);
            if ($lec_course) {
                $results = StudentResult::with(['student', 'course'])->where('prog_type_id', $lec_course->programme_type_id);
                $results->where('session', $this->session);
                $results->whereCourseId($lec_course->course_id);
                $results->whereProgTypeId($lec_course->programme_type_id);
                $results->whereLevelId($lec_course->course->levels);
                if ($this->search_param) $results->where('matric_number', 'like', '%' . $this->search_param . '%');
                $results = $results->orderByRaw('matric_number asc')->paginate(PAGINATE_SIZE);
            }
        }

        $courses = LecturerCourse::with(['lecturer', 'course', 'programmeType'])->where('lecturer_id', auth()->user()->id)->whereSessionYear($this->app_session)->get();
        $sessions = SchoolSession::orderBy('year')->get(['session', 'year']);

        return view('livewire.lecturer.lecturer-view-results-component', compact('results', 'courses', 'sessions', 'lec_course'));
    }
}
