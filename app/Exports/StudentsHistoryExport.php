<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentsHistoryExport implements FromView
{
    public $data, $level_id, $param, $adm_year; 

    public function __construct($data, $level_id, $param, $adm_year)
    {
        $this->data = $data;
        $this->level_id = $level_id;
        $this->param = $param;
        $this->adm_year = $adm_year;
    }

    public function view() : View
    {
        return view('exports.student.history', [
            'data' => $this->data,
            'level_id' => $this->level_id,
            'param' => $this->param,
            'adm_year' => $this->adm_year
        ]);
    }
}
