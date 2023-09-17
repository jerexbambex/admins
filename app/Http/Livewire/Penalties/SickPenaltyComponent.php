<?php

namespace App\Http\Livewire\Penalties;

use App\Models\CourseReg;
use App\Models\SchoolSession;
use App\Models\Student;
use App\Models\StudentPenalty;
use App\Models\StudentSession;
use Livewire\Component;

class SickPenaltyComponent extends Component
{
    public $layout, $penalty, $validated, $semesters, $programme, $admission_year, $prog_type_id;

    public $std_no, $session, $semester, $level_id, $description, $log_id;

    public $search_param, $date_penalized, $full_name;

    protected $rules = [
        'std_no'   =>  'required',
        'session'       =>  'required',
        'level_id'       =>  'required',
        'description'       =>  'required',
        'penalty'       =>  'required',
        'log_id'       =>  'required',
        'date_penalized'    =>  'required|date'
    ];

    public function mount()
    {
        $this->layout = 'form';
        $this->penalty = 'sick';
        $this->validated = false;
        $this->semesters = 2;
        $this->semester = 'all';
        $this->programme = "";
        $this->admission_year = '';
        $this->full_name = '';
    }

    public function validateStudent()
    {
        $this->validate(['std_no' => 'required']);

        if ($this->std_no) {
            $student = Student::whereMatricNo($this->std_no)->orWhere('matset', $this->std_no)
                ->first(['std_logid', 'stdprogrammetype_id', 'stdprogramme_id', 'std_admyear', 'status', 'surname', 'firstname', 'othernames']);
            if (!$student) return session()->flash('error_toast', "Student does not exist!");
            elseif ($student->status == 'expelled') return session()->flash('error_toast', "Student already expelled!");
            elseif ($student->status == 'withdrawn') return session()->flash('error_toast', "Student already withdrawn!");
            elseif ($student->status != 'active') return session()->flash('error_toast', "Student status is `$student->status`!");

            $this->log_id = $student->std_logid;
            $this->validated = true;
            $this->semesters = $student->stdprogrammetype_id == 2 ? 3 : 2;
            $this->prog_type_id = $student->stdprogrammetype_id;
            $this->programme = $student->stdprogramme_id;
            $this->admission_year = $student->std_admyear;
            $this->full_name = $student->full_name;
        }
    }

    public function addPenalty($check, $data)
    {
        if ($data) {
            unset($check['description']);
            unset($check['date_penalized']);
            $check['semester_id'] = $data['semester_id'];

            if (StudentPenalty::where($check)->count() === 0) {
                $session_check = [
                    'admission_year'    =>  $this->admission_year,
                    'session'           =>  $this->session,
                    'semester'          =>  $data['semester_id'],
                    'level_id'          =>  $this->level_id,
                    'log_id'            =>  $data['log_id']
                ];
                if (StudentSession::where($session_check)->count() > 0) {
                    StudentSession::where($session_check)->delete();
                } else {
                    $session_data = $session_check;
                    $other_data = [
                        'prog_id'       => in_array($this->level_id, [1, 2]) ? 1 : 2,
                        'prog_type_id'  => $this->prog_type_id
                    ];
                    $session_data = array_merge($session_data, $other_data);

                    StudentSession::where($session_check)->create($session_data);

                    StudentSession::where($session_check)->delete();
                }
                $course_check = [
                    'log_id'            =>  $data['log_id'],
                    'cyearsession'      =>  explode('/', $data['session'])[0],
                    'csemester'         =>  SEMESTERS[$data['semester_id']],
                    'clevel_id'         =>  $data['level_id']
                ];
                CourseReg::where($course_check)->delete();
                if (StudentPenalty::create($data)) return true;
            }
        }
        return false;
    }

    public function submit()
    {
        $data1 = $this->validate();

        if ($this->validated) {
            $user_id = auth()->user()->id;
            if ($this->admission_year > explode('/', $this->session)[0] && in_array($this->level_id, [1, 3]))
                return session()->flash('error_toast', "Invalid session and level mismatch!");

            if ($this->semester == 'all') {
                foreach (range(1, $this->semesters) as $semester_id) {
                    $data2 = compact('user_id', 'semester_id');

                    $data = array_merge($data1, $data2);
                    $this->addPenalty($data1, $data);
                }
            } else {
                $semester_id = $this->semester;
                $data2 = compact('user_id', 'semester_id');

                $data = array_merge($data1, $data2);
                $this->addPenalty($data1, $data);
            }
            session()->flash('success_toast', "Penalty set successfully!");
            $this->reset();
            $this->mount();
        }
    }

    public function render()
    {
        $sessions = $penalties = [];
        if ($this->layout == 'form') {
            if ($this->admission_year) $sessions = SchoolSession::where('year', '>=', $this->admission_year)->get(['session']);
            else $sessions = SchoolSession::all(['session']);
        } else {
            $penalties = StudentPenalty::select('student_penalties.*')->with('student');
            if ($search = explode(' ', $this->search_param)) {
                $penalties->join('stdprofile', 'stdprofile.std_logid', 'student_penalties.log_id');
                if (count($search) == 2) $penalties->where(function ($query) use ($search) {
                    $query->where('surname', 'like', '%' . $search[0] . '%')
                        ->where('firstname', 'like', '%' . $search[1] . '%');
                });
                elseif (count($search) == 3) $penalties->where(function ($query) use ($search) {
                    $query->where('surname', 'like', '%' . $search[0] . '%')
                        ->where('firstname', 'like', '%' . $search[1] . '%')
                        ->where('othernames', 'like', '%' . $search[2] . '%');
                });
                else $penalties->where(function ($query) use ($search) {
                    $query->where('matric_no', 'like', '%' . $search[0] . '%')
                        ->orwhere('matset', 'like', '%' . $search[0] . '%')
                        ->orwhere('surname', 'like', '%' . $search[0] . '%')
                        ->orwhere('firstname', 'like', '%' . $search[0] . '%')
                        ->orwhere('othernames', 'like', '%' . $search[0] . '%');
                });
            }
            $penalties = $penalties->wherePenalty($this->penalty)->latest()->paginate(PAGINATE_SIZE * 2);
        }
        return view('livewire.penalties.sick-penalty-component', compact('sessions', 'penalties'));
    }
}
