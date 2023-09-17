<?php

namespace App\Http\Livewire\Dr;

use App\Models\Course;
use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Faculty;
use App\Models\Level;
use App\Models\Programme;
use App\Models\SchoolSession;
use Livewire\Component;

class CourseStructureComponent extends Component
{
    public $faculty, $department, $option, $level, $prog_id, $is_cec, $semester_id, $session_year;

    public $courses = [], $editable = false, $to_set, $count;

    public function mount()
    {
        $this->faculty = "";
        $this->department = "";
        $this->option = "";
        $this->level = 1;
        $this->prog_id = 1;
        $this->is_cec = 0;
        $this->semester_id = 1;
        $this->session_year = SchoolSession::first()->year;
        $this->courses = [];
        $this->editable = false;
        $this->count = 1;
        $this->to_set = '';

        $user = auth()->user();
        if ($user->prog_type_id == 2) $this->is_cec = 1;
    }

    public function loadCourses()
    {
        $this->courses = [];
        $this->editable = false;
        if ($this->faculty && $this->department && $this->option && $this->semester_id) {
            $courses = Course::whereLevels($this->level)->whereSemester(SEMESTERS[$this->semester_id]);
            $courses->whereStdcourse($this->option)->whereForCec($this->is_cec)->where('for_set', 'like', "%$this->session_year%");
            foreach ($courses->get() as $course) $this->addCourse($course);
        } else {
            session()->flash('error_toast', 'Filter parameters not complete!');
        }
    }

    public function addCourse($course = NULL)
    {
        $data = [];
        $data['id'] = $course ? $course->thecourse_id : '';
        $data['title'] = $course ? $course->thecourse_title : '';
        $data['unit'] = $course ? $course->thecourse_unit : '';
        $data['code'] = $course ? $course->thecourse_code : '';
        $data['cat'] = $course ? $course->thecourse_cat : 'C';
        $data['sem'] = $course ? $course->semester : SEMESTERS[$this->semester_id];
        $data['for_set'] = $course ? $course->for_set : [$this->session_year];
        $this->courses[] = $data;
    }

    public function removeCourse($index)
    {
        $courses = $this->courses;
        unset($courses[$index]);
        $this->courses = $courses;
    }

    public function newCourse()
    {
        if (is_numeric($this->count)) {
            $counts = (int) abs(ceil($this->count));
            // dd($counts);
            foreach (range(1, $counts) as $c)
                $this->addCourse();
        } else dd('failed');
    }

    public function submit()
    {
        foreach ($this->courses as $c) {
            if ($c['id']) {
                $course = Course::find($c['id']);
                $this->updateCourse($course, $c);
            } else {
                $this->createCourse($c);
            }
        }

        $this->courses = [];
        $this->editable = false;
        $this->count = 1;
        $this->to_set = '';

        return session()->flash('success_alert', 'Upload Successful!');
    }

    public function createCourse(array $course)
    {
        if ($this->to_set) {
            if (!in_array($this->to_set, $course['for_set'])) //then
                $course['for_set'][] = $this->to_set;
        } else {
            if (!in_array($this->session_year, $course['for_set'])) //then
                $c['for_set'][] = $this->session_year;
        }
        $data = [
            'thecourse_title'   =>  $course['title'],
            'thecourse_unit'    =>  $course['unit'],
            'thecourse_code'    =>  $course['code'],
            'semester'          =>  $course['sem'],
            'thecourse_cat'     =>  $course['cat'],
            'for_set'           =>  $course['for_set'],
            'for_cec'           =>  $this->is_cec,
            'theschool_id'           =>  $this->faculty,
            'levels'           =>  $this->level,
            'stdcourse'           =>  $this->option,
            'department_id'           =>  $this->department
        ];
        // dd($data);
        // $check = $data;
        // unset($check['for_set']);
        // if (!Course::where($check)->where('for_set', 'like', $course['for_set'])->count())
        Course::create($data);
    }

    public function updateCourse(Course $course, array $c)
    {
        if ($course) {
            if (
                $this->to_set && ($course->thecourse_title != $c['title'] ||
                    $course->thecourse_unit != $c['unit'] ||
                    $course->thecourse_code != $c['code'] ||
                    $course->thecourse_cat != $c['cat']
                )
            ) {
                $c['for_set'] = [$this->to_set];
                return $this->createCourse($c);
            }

            if ($this->to_set) {
                if (!in_array($this->to_set, $c['for_set'])) //then
                    $c['for_set'][] = $this->to_set;
            } else {
                if (!in_array($this->session_year, $c['for_set'])) {
                    $c['for_set'][] = $this->session_year;
                } else {
                    if ($c['for_set'] <> [$this->session_year]) {
                        if (
                            $course->thecourse_title != $c['title'] ||
                            $course->thecourse_unit != $c['unit'] ||
                            $course->thecourse_code != $c['code'] ||
                            $course->thecourse_cat != $c['cat']
                        ) {
                            $index = array_search($this->session_year, $c['for_set']);
                            if ($index !== false) {
                                unset($c['for_set'][$index]);
                            }
                            $course->update(['for_set' => $c['for_set']]);
                            $c['for_set'] = [$this->session_year];
                            return $this->createCourse($c);
                        }
                    }
                }
            }

            return $this->courseUpdate($course, $c);
        }
    }

    public function courseUpdate(Course $course, array $c)
    {
        return $course->update([
            'thecourse_title'   =>  $c['title'],
            'thecourse_unit'    =>  $c['unit'],
            'thecourse_code'    =>  $c['code'],
            'semester'          =>  $c['sem'],
            'thecourse_cat'     =>  $c['cat'],
            'for_set'           =>  $c['for_set'],
            'for_cec'           =>  $this->is_cec
        ]);
    }

    public function clearAll()
    {
        $this->courses = [];
    }

    public function render()
    {
        $faculties = Faculty::all();
        $departments = $options = [];
        if ($this->faculty) $departments = Department::whereFacId($this->faculty)->get(['departments_id', 'departments_name']);
        if ($this->department) $options = DeptOption::whereDeptId($this->department)->whereProgId($this->prog_id)->get(['do_id', 'programme_option']);

        $progs = Programme::all();
        $levels = Level::whereProgrammeId($this->prog_id)->get();
        $sch_sessions = SchoolSession::all();

        return view('livewire.dr.course-structure-component', compact('faculties', 'departments', 'options', 'progs', 'levels', 'sch_sessions'));
    }
}
