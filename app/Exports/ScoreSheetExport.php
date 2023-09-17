<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ScoreSheetExport implements FromView
{
    public $data, $level_id, $session, $set, $code, $course_id,
        $dept, $opt, $level, $prog_type, $course_title;

    public function __construct(
        $data,
        String $code = '',
        Int $course_id = 0,
        String $session = '',
        String $set = '',
        String $dept = '',
        String $opt = '',
        String $level = '',
        String $prog_type = '',
        String $course_title = ''
    ) {
        $this->data = $data;
        $this->session = $session;
        $this->set = $set;
        $this->code = $code;
        $this->course_id = $course_id;
        $this->dept = $dept;
        $this->opt = $opt;
        $this->level = $level;
        $this->prog_type = $prog_type;
        $this->course_title = $course_title;
    }

    public function view(): View
    {
        return view('exports.scoresheet.history', [
            'data' => $this->data,
            'code'  =>  $this->code,
            'course_id'  =>  $this->course_id,
            'session'   =>  $this->session,
            'set'   =>  $this->set,
            'dept' => $this->dept,
            'opt' => $this->opt,
            'level' => $this->level,
            'prog_type' => $this->prog_type,
            'course_title'  =>  $this->course_title
        ]);
    }
}
