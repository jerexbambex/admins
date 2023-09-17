<?php

namespace App\Exports;

use App\Services\ResultRevalidationService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ResultRevalidationExport implements FromView
{
    public
        $course_id,
        $session,
        $semester,
        $level_id;

    function __construct(
        $course_id,
        $session,
        $semester,
        $level_id
    ) {
        $this->course_id = $course_id;
        $this->session = $session;
        $this->semester = $semester;
        $this->level_id = $level_id;
    }

    public function view(): View
    {
        return view('exports.result_revalidation', [
            'course' => ResultRevalidationService::getCourse($this->course_id),
            'course_regs'   =>  ResultRevalidationService::getCourseRegs($this->course_id, $this->session, $this->semester, $this->level_id)
        ]);
    }
}
