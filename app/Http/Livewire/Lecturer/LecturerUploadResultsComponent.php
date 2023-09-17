<?php

namespace App\Http\Livewire\Lecturer;

use App\Imports\ScoreSheetImport;
use App\Models\LecturerCourse;
use App\Models\SchoolSession;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class LecturerUploadResultsComponent extends Component
{
    public $session, $file, $course_id, $app_session;
    private $courseRef;

    protected $rules = [
        'course_id'    =>  'required',
        'session'    =>  'required',
        'file'    =>  'required|file|mimes:xlsx,xls,csv',
    ];

    protected $messages = [
        'course_id.required'    =>  'Course field is required'
    ];

    public function mount()
    {
        $this->app_session = session()->get('app_session');
        $this->session = session()->get('sch_session');
    }

    public function updated($prop)
    {
        if ($prop == 'course_id') $this->courseRef = LecturerCourse::find($this->course_id);
        elseif ($prop == 'session') $this->app_session = explode('/', $this->session)[0];
        $this->validateOnly($prop);
    }

    public function upload()
    {
        $this->validate();
        $course_id = base64_encode($this->course_id);
        $session = base64_encode($this->session);
        $file_path = base64_encode($this->file->path());

        return redirect()->route('lecturer.result.upload', [
            'course_id' => $course_id,
            'session' => $session,
            'file_path' => $file_path
        ]);
        // $this->courseRef = LecturerCourse::find($this->course_id);

        // if (!$this->courseRef || !$this->session)
        //     return session()->flash('error_toast', 'Unable to upload!');

        // Excel::import(new ScoreSheetImport($this->courseRef, $this->session), $this->file->path());
    }

    use WithFileUploads;

    public function render()
    {
        $sessions = SchoolSession::all(['session', 'year']);
        $courses = LecturerCourse::with('course')->where('lecturer_id', auth()->user()->id)->whereSessionYear($this->app_session)->get();

        return view('livewire.lecturer.lecturer-upload-results-component', compact('sessions', 'courses'));
    }
}
