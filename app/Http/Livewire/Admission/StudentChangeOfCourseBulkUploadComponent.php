<?php

namespace App\Http\Livewire\Admission;

use App\Exports\StudentChangeOfCourseExport;
use App\Imports\StudentChangeOfCourseImport;
use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Faculty;
use App\Models\Programme;
use App\Models\Student;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class StudentChangeOfCourseBulkUploadComponent extends Component
{
    public $faculty, $department, $programme, $option, $file;

    protected $rules = [
        'faculty'       =>  'required',
        'department'    =>  'required',
        'programme'     =>  'required',
        'option'        =>  'required',
        'file'          =>  'required|file|mimes:xlsx,xls,csv',
    ];

    public function mount()
    {
        $this->faculty = "";
        $this->department = "";
        $this->programme = "";
        $this->option = "";
    }

    public function updated($field)
    {
        if ($field == 'faculty') {
            $this->department = "";
            $this->option = "";
        } elseif ($field == 'department' || $field == 'programme') {
            $this->option = "";
        }

        $this->validateOnly($field);
    }

    // public function submit()
    // {
    //     $this->validate();

    //     return Excel::import(new StudentChangeOfCourseImport($this->faculty, $this->department, $this->programme, $this->option), $this->file->path());
    // }

    public function download_template()
    {
        try {
            $date = Carbon::now()->format('d-m-Y h-i-s-a');
            $filename = "students-change-of-course-bulk-upload-$date";
            return Excel::download(new StudentChangeOfCourseExport, "$filename.xlsx");
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'An error occured');
        }
    }

    use WithFileUploads;

    public function render()
    {
        $faculties = Faculty::all(['faculties_id', 'faculties_name']);
        $departments = $options = [];
        if ($this->faculty) $departments = Department::whereFacId($this->faculty)->get(['departments_id', 'departments_name']);
        if ($this->department && $this->programme) $options = DeptOption::whereDeptId($this->department)->whereProgId($this->programme)->get(['do_id', 'programme_option']);
        $programmes = Programme::all(['programme_id', 'programme_name']);
        return view(
            'livewire.admission.student-change-of-course-bulk-upload-component',
            compact(
                'faculties',
                'departments',
                'programmes',
                'options'
            )
        );
    }
}
