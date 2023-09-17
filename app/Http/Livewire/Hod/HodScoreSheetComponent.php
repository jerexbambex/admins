<?php

namespace App\Http\Livewire\Hod;

use App\Exports\ScoreSheetExport;
use App\Models\Course;
use App\Models\Department;
use App\Models\ProgrammeType;
use App\Models\SchoolSession;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class HodScoreSheetComponent extends Component
{
    public $course_id = 0, $get_course, $programme_type;
    public $search_param, $progtypes;
    public $current_session, $current_set, $students_sets;

    public $prog_type, $dept, $opt, $level;

    public function mount($course)
    {
        $this->course_id = $course;
        $this->get_course = Course::find($course);
        $this->programme_type = 1;
        $this->current_session = session()->get('sch_session');
        $this->students_sets = $this->get_course->for_set;
        $this->current_set = $this->students_sets[0];

        $this->prog_type = ProgrammeType::select(['programmet_name'])->find($this->programme_type)->programmet_name;
        $this->dept = Department::select(['departments_name'])->find(auth()->user()->department_id)->departments_name;
        $this->opt = $this->get_course->department_course()->first(['programme_option'])->programme_option;
        $this->level = $this->get_course->level_text;

        if ($this->get_course->for_cec) {
            $this->progtypes = ProgrammeType::whereProgrammetId(2)->get()->toArray();
        } else {
            $this->progtypes = ProgrammeType::where('programmet_id', '!=', 2)->get()->toArray();
        }
    }

    public function updated($field)
    {
        if ($field == 'programme_type') {
            $this->prog_type = ProgrammeType::select(['programmet_name'])->find($this->programme_type)->programmet_name;
        }
    }

    public function getStudents($param = 'view')
    {
        $students = Student::select([
            'stdprofile.*',
            'student_sessions.log_id',
            'student_sessions.session'
        ]);
        $students = $students->with(['department', 'course', 'programme', 'level', 'progType', 'faculty']);
        $students = $students->join('student_sessions', 'student_sessions.log_id', 'stdprofile.std_logid');
        $students = $students->where('student_sessions.session', $this->current_session);
        $students = $students->where('student_sessions.admission_year', $this->current_set);
        $students = $students->where('student_sessions.semester', SEMESTER_KEYS[$this->get_course->semester]);
        $students = $students->where('stdprofile.stdcourse', $this->get_course->stdcourse);
        $students = $students->where('stdprofile.stdprogrammetype_id', $this->programme_type);
        $students = $students->where('stdprofile.std_admyear', $this->current_set);

        if ($this->search_param) {
            $search_param = $this->search_param;
            $students = $students->where(function ($query) use ($search_param) {
                $query->where('matric_no', 'like', '%' . $search_param . '%')
                    ->orWhere('matset', 'like', '%' . $search_param . '%');
            });
        }
        if ($this->programme_type) $students = $students->where('stdprogrammetype_id', $this->programme_type);
        $students = $students->groupby('stdprofile.std_logid', 'student_sessions.log_id', 'student_sessions.session');
        $students = $students->orderBy('stdprofile.surname', 'asc');
        return $students;
    }

    public function download()
    {
        $students = $this->getStudents()->get();
        $course_code = $this->get_course->thecourse_code;
        $course_id = $this->get_course->thecourse_id;
        $course_title = $this->get_course->thecourse_title;
        if (
            !$this->dept ||
            !$this->opt ||
            !$this->level ||
            !$this->prog_type
        ) return session()->flash('error_toast', "Unable to download");

        return Excel::download(new ScoreSheetExport(
            $students,
            $course_code,
            $course_id,
            $this->current_session,
            $this->current_set,
            $this->dept,
            $this->opt,
            $this->level,
            $this->prog_type,
            $course_title
        ), "$this->dept - $this->opt - $this->level - $this->prog_type - $course_code scoresheet (Set: $this->current_set).xlsx");
    }

    use WithPagination;

    public function render()
    {
        $students = $this->getStudents()->paginate(PAGINATE_SIZE);
        // $progtypes = ProgrammeType::all();
        $sessions = SchoolSession::all(['session']);
        return view('livewire.hod.hod-score-sheet-component', compact('students', 'sessions'));
    }
}
