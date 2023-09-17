<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ApplicantCBTScoreExport implements FromView
{

    public function view(): View
    {
        return view('exports.admission.applicants-cbt-score');
    }
}
