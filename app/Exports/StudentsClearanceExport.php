<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentsClearanceExport implements FromView
{
    public $data, $file_name;

    public function __construct($data, $file_name)
    {
        $this->data = $data;
        $this->file_name = $file_name;
    }

    public function view(): View
    {
        return view('exports.student.clearance_list', [
            'data' => $this->data,
            'file_name' => $this->file_name,
        ]);
    }
}
