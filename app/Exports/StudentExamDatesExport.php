<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentExamDatesExport implements FromView
{
    public $data; 

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view() : View
    {
        return view('exports.student.exam_dates', [
            'data' => $this->data
        ]);
    }
}
