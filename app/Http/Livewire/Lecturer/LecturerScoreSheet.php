<?php

namespace App\Http\Livewire\Lecturer;

use App\Exports\ScoreSheetExport;
use App\Models\Course;
use App\Models\Department;
use App\Models\LecturerCourse;
use App\Models\ProgrammeType;
use App\Models\SchoolSession;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class LecturerScoreSheet extends Component
{
    public $course_id = 0, $get_course, $programme_type = 0;
    public $search_param, $code, $lecturerCourse;
    public $current_session, $current_set;

    public $prog_type, $dept, $opt, $level;
    public $students_sets = [];

    public function mount($course, $id)
    {
        $lecturerCourse = LecturerCourse::find($id);
        $this->lecturerCourse = $lecturerCourse;
        $this->course_id = $course;
        $this->get_course = Course::find($this->course_id);
        $this->code = $this->get_course->thecourse_code;
        $this->programme_type = $lecturerCourse->programme_type_id;
        $this->current_session = session()->get('sch_session');
        $this->students_sets = $this->get_course->for_set;
        $this->current_set = $this->students_sets[0];

        $this->prog_type = $lecturerCourse->programmeType->programmet_name;
        $this->dept = Department::select(['departments_name'])->find(auth()->user()->department_id)->departments_name;
        $this->opt = $this->get_course->department_course()->first(['programme_option'])->programme_option;
        $this->level = $this->get_course->level_text;
    }

    public function getStudents($param = 'view')
    {
        $students = Student::select([
            'stdprofile.*',
            'student_sessions.log_id',
            'student_sessions.session'
        ]);
        $students->with(['department', 'course', 'programme', 'level', 'progType', 'faculty']);
        $students->join('student_sessions', 'student_sessions.log_id', 'stdprofile.std_logid');
        $students->where('student_sessions.session', $this->current_session);
        $students->where('student_sessions.admission_year', $this->current_set);
        $students->where('student_sessions.semester', SEMESTER_KEYS[$this->get_course->semester]);
        $students->where('stdprofile.stdcourse', $this->get_course->stdcourse);
        $students->where('stdprofile.stdprogrammetype_id', $this->programme_type);
        $students->where('stdprofile.std_admyear', $this->current_set);

        if ($this->search_param) {
            $search_param = $this->search_param;
            $students->where(function ($query) use ($search_param) {
                $query->where('matric_no', 'like', '%' . $search_param . '%')
                    ->orWhere('matset', 'like', '%' . $search_param . '%');
            });
        }
        if ($this->programme_type) $students->where('stdprogrammetype_id', $this->programme_type);
        $students->groupby('stdprofile.std_logid', 'student_sessions.log_id', 'student_sessions.session');
        return $students->orderBy('stdprofile.surname', 'asc');
    }

    public function download()
    {
        $students = $this->getStudents('download');
        $students = $students->get();
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


    public function downloadSheet()
    {
        $students = $this->getStudents('download');
        $students = $students->get();
        return redirect()->route('lecturer.result.blank-sheet', ['course_id' => $this->course_id, 'encoded_session' => base64_encode($this->current_session), 'lec_course_id' => $this->lecturerCourse->id]);
    }

    use WithPagination;

    public function render()
    {
        $students = $this->getStudents('view');
        $students = $students->paginate(PAGINATE_SIZE);
        $sessions = SchoolSession::all(['session']);
        // $progtypes = ProgrammeType::all(['programmet_id', 'programmet_name']);
        return view('livewire.lecturer.lecturer-score-sheet', compact('students', 'sessions'));
    }
}
