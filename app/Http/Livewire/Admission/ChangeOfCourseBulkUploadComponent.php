<?php

namespace App\Http\Livewire\Admission;

use App\Imports\ChangeOfCourseImport;
use App\Models\Department;
use App\Models\Faculty;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ChangeOfCourseBulkUploadComponent extends Component
{
    public $faculty_id, $department_id, $file;

    protected $rules = [
        'faculty_id'    =>  'required',
        'department_id'    =>  'required',
        'file'    =>  'required|file|mimes:xlsx,xls,csv',
    ];

    public function updated($field_name)
    {
        $this->validateOnly($field_name);
    }

    // public function upload()
    // {
    //     $this->validate();
    //     // dd($this->file);
    //     Excel::import(new ChangeOfCourseImport($this->faculty_id, $this->department_id), $this->file->path());
    // }

    use WithFileUploads;

    public function render()
    {
        $faculties = Faculty::all();
        $departments = [];
        if ($this->faculty_id) $departments = Department::whereFacId($this->faculty_id)->get();
        return view('livewire.admission.change-of-course-bulk-upload-component', compact('faculties', 'departments'));
    }
}
