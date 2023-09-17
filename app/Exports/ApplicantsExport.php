<?php

namespace App\Exports;

use App\Services\AdmissionsService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ApplicantsExport implements FromView
{
    public $session,
        $faculty_id,
        $department_id,
        $prog_id,
        $prog_type_id,
        $adm_status,
        $search;

    public function __construct(
        $session,
        $faculty_id,
        $department_id,
        $prog_id,
        $prog_type_id,
        $adm_status,
        $search
    ) {
        $this->session = $session;
        $this->faculty_id = $faculty_id;
        $this->department_id = $department_id;
        $this->prog_id = $prog_id;
        $this->prog_type_id = $prog_type_id;
        $this->adm_status = $adm_status;
        $this->search = $search;
    }

    public function view(): View
    {
        return view('exports.admission.applicants', [
            'applicants' => AdmissionsService::downloadApplicants(
                $this->session,
                $this->faculty_id,
                $this->department_id,
                $this->prog_id,
                $this->prog_type_id,
                $this->adm_status,
                $this->search
            ),
            'prog_id'   =>  $this->prog_id
        ]);
    }
}
