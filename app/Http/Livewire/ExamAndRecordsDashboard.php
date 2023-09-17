<?php

namespace App\Http\Livewire;

use App\Models\BosLog;
use App\Models\Student;
use App\Models\StudentSession;
use App\Services\ResultService;
use Livewire\Component;

class ExamAndRecordsDashboard extends Component
{
    public $matric_number;
    public $result_data, $bos_log, $student_log_id;

    protected $rules = [
        'matric_number' => 'required|numeric|exists:stdprofile,matric_no'
    ];

    public function mount()
    {
        $this->matric_number = '';
        $this->result_data = [];
        $this->bos_log = $this->student_log_id = null;
    }

    public function updated()
    {
        $this->result_data = [];
        $this->bos_log = $this->student_log_id = null;
        $this->validate();
    }

    public function searchStudentResult()
    {
        $this->validate();

        $studentSession = StudentSession::whereMatricNumber($this->matric_number)->orderByRaw('session, level_id, semester desc')->first();

        $student = Student::whereMatricNo($this->matric_number)->first();
        if (!$student) {
            return session()->flash('error_toast', 'Student not found!');
        }
        if (!$student->canGraduate(
            $studentSession->session,
            $studentSession->semester,
            $studentSession->level_id
        )) {
            return session()->flash('error_toast', 'Student cannot graduate at the moment!');
        }

        $bos_log = BosLog::whereOptionId($student->stdcourse)->whereAdmYear($student->std_admyear)
            ->whereProgId($student->stdprogramme_id)->whereProgTypeId($student->stdprogrammetype_id)
            ->whereSession($studentSession->session)
            ->whereLevelId($studentSession->level_id)
            ->whereSemesterId($studentSession->semester)
            ->first();

        if (!$bos_log) {
            return session()->flash('error_toast', 'No B.O.S Record found!');
        }

        $this->bos_log = $bos_log;

        $this->result_data = ResultService::addStudentRunningList(
            $student,
            $bos_log->session,
            $bos_log->semester_id,
            $bos_log->level_id
        );

        $this->student_log_id = $student->std_logid;
    }

    public function render()
    {
        return view('livewire.exam-and-records-dashboard');
    }
}
