<?php

namespace App\Http\Livewire\Hod;

use App\Models\Course;
use App\Models\LecturerCourse;
use App\Models\ProgrammeType;
use App\Services\UserService;
use Livewire\Component;
use Livewire\WithPagination;

class AssignLecturerToCourseComponent extends Component
{
    public $course_id = 0, $lecturer_id = 0, $programme_type_id;
    public $to_assign_course, $sch_session;
    public $programmeTypes = [];

    protected $rules = [
        'course_id'     =>  'required',
        'lecturer_id' =>  'required',
        'programme_type_id' =>  'required'
    ];

    protected $messages = [
        'lecturer_id' =>  'Please select a lecturer',
        'programme_type_id' =>  'Please select a programme type',
    ];

    public function mount($course)
    {
        $this->course_id = $course;
        $app_session = session()->get('app_session');
        $this->sch_session = session()->get('sch_session');
        $this->to_assign_course = Course::whereRaw("for_set like '%$app_session%'")->find($course);
        if (!$this->to_assign_course) {
            session()->flash('error_toast', "Wrong parameter or parameter mismatch!");
            return redirect()->route('hod.courses', ['param' => 'assign']);
        }


        if ($this->to_assign_course->for_cec) {
            $this->programmeTypes = ProgrammeType::whereProgrammetId(2)->get()->toArray();
        } else {
            $this->programmeTypes = ProgrammeType::where('programmet_id', '!=', 2)->get()->toArray();
        }
    }

    public function update($propName)
    {
        $this->validateOnly($propName);
    }

    public function deleteAssigned(LecturerCourse $lecturerCourse)
    {
        $lecturerCourse->delete();
        session()->flash('success_toast', 'Assigned course delete successful!');
    }

    public function submit()
    {
        $data = $this->validate();
        $data['assigned_by'] = auth()->user()->id;
        $session_year = explode('/', $this->sch_session)[0];
        $data['session_year'] = $session_year;
        $set = session()->get('app_session');
        $assigned = LecturerCourse::join('courses', 'courses.thecourse_id', 'lecturer_courses.course_id')
            ->whereCourseId($this->course_id)->whereSessionYear($session_year)
            ->whereRaw("courses.for_set like '%$set%'")->whereProgrammeTypeId($this->programme_type_id);
        $courseAssigned = $assigned->count();
        if ($courseAssigned) {
            return session()->flash('error_toast', 'Course already assigned in that session!');
        } else {
            if (LecturerCourse::create($data)) session()->flash('success_alert', 'Lecturer successfully assigned!');
            else return session()->flash('error_toast', 'Unable to perform action!');
        }
        $this->mount($this->course_id);
    }

    use WithPagination;

    public function render()
    {
        $session_year = explode('/', $this->sch_session)[0];
        $set = session()->get('app_session');
        $coursesAssigned = LecturerCourse::join('courses', 'courses.thecourse_id', 'lecturer_courses.course_id')
            ->whereCourseId($this->course_id)->whereSessionYear($session_year)
            ->whereRaw("courses.for_set like '%$set%'")->latest()->paginate(PAGINATE_SIZE / 2);
        // $programmeTypes = ProgrammeType::all(['programmet_id', 'programmet_name']);
        $lecturers = UserService::fetchUsers('lecturer', auth()->user()->department_id)->get();
        return view('livewire.hod.assign-lecturer-to-course-component', compact('coursesAssigned', 'lecturers'));
    }
}
