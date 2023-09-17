<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentsDataAdmissionsExport implements FromView
{
    public $students, $file_name;

    public function __construct($students, $file_name)
    {
        $this->students = $students;
        $this->file_name = $file_name;
    }

    public function view(): View
    {
        return view('exports.student.admissions_students_data', [
            'students' => $this->students,
            'file_name' => $this->file_name,
        ]);
    }
}
