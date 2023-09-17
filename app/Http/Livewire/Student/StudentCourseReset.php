<?php

namespace App\Http\Livewire\Student;

use App\Models\CourseReg;
use App\Models\Level;
use App\Models\SchoolSession;
use App\Models\Student;
use Livewire\Component;

class StudentCourseReset extends Component
{
    public $student_data, $level, $session_ref, $semester;

    protected $rules = [
        'student_data'  => 'required',
        'level' => 'required',
        'session_ref'   => 'required',
        'semester'   => 'required'
    ];

    public function mount()
    {
        $this->student_data = '';
        $this->level = '';
        $this->session_ref = '';
    }

    public function resetStudentCourse()
    {
        $this->validate();
        $student = Student::whereMatricNo($this->student_data)->orWhere('matset', $this->student_data)->first();
        if(!$student) return session()->flash('error_toast', 'Student does not exist!');

        $course_regs = CourseReg::whereLogId($student->std_logid)->whereClevelId($this->level);
        $course_regs = $course_regs->whereCyearsession($this->session_ref);
        $course_regs = $course_regs->whereCsemester($this->semester);

        if(!$course_regs->count()) return session()->flash('error_toast', 'Student course registration not found!');

        $course_regs->delete();

        return session()->flash('success_toast', 'Student course registration cleared!');
    }

    public function render()
    {
        $sessions = SchoolSession::all();
        $levels = Level::all();
        return view('livewire.student.student-course-reset', compact('sessions', 'levels'));
    }
}
