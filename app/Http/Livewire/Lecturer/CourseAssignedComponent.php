<?php

namespace App\Http\Livewire\Lecturer;

use App\Models\LecturerCourse;
use App\Models\SchoolSession;
use Livewire\Component;

class CourseAssignedComponent extends Component
{
    public $_session, $_level, $_semester;

    public function mount()
    {
        $this->_session = session()->get('app_session');
        $this->_level = "";
        $this->_semester = "First Semester";
    }

    public function updated($field)
    {
        if($field == '_session'){
            session()->put('app_session', $this->_session);
            session()->put('sch_session', sprintf('%s/%s', $this->_session, (int)$this->_session + 1));
        }
    }

    public function render()
    {
        $courses = LecturerCourse::select(['lecturer_courses.*'])->where('lecturer_id', auth()->user()->id)->whereSessionYear($this->_session);
        $courses->join('courses', 'courses.thecourse_id', 'lecturer_courses.course_id')->where('courses.semester', $this->_semester);
        if($this->_level) $courses->where('courses.levels', $this->_level);
        $courses = $courses->get();

        $sessions = SchoolSession::get(['year', 'session']);
        return view('livewire.lecturer.course-assigned-component', compact('courses', 'sessions'));
    }
}
