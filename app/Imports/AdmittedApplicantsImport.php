<?php

namespace App\Imports;

use App\Models\Applicant;
use App\Models\Department;
use App\Models\DeptOption;
use App\Models\Portal;
use App\Services\AdmissionsService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AdmittedApplicantsImport implements ToCollection
{
    public $adm_year;

    public function __construct($adm_year)
    {
        $this->adm_year = $adm_year;
    }
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collections)
    {
        $c = 1;
        $count = 0;
        $failed = 0;
        if (!$this->adm_year) return session()->flash('error_alert', 'Admission year not selected');
        $prog_type = auth()->user()->prog_type_id;

        // dd($collections);
        foreach ($collections as $row) {
            // dd($row);
            if ($c >= 2) {
                $appno = $row[1];
                $applicant = Applicant::where('app_no', $appno);
                if ($prog_type) $applicant->where('std_programmetype', $prog_type);
                $applicant = $applicant->first();
                $exists = (bool) Portal::where('appno', $appno)->count();
                // dd($appno, $exists);

                if ($applicant && !$exists) {
                    if (AdmissionsService::admitStudent($applicant)) $count++;
                } else $failed++;
            }
            $c++;
        }
        session()->flash('success_alert', "$count Applicants Uploaded successfully!");

        if ($failed) {
            session()->flash('error_toast', "$failed Applicants Upload failed!");
        }
    }
}
