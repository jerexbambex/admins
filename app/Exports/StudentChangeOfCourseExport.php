<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentChangeOfCourseExport implements FromView
{
    public function view(): View
    {
        return view('exports.admission.students-change-of-course-upload-template');
    }
}
