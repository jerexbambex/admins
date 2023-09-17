<?php

namespace App\Imports;

use App\Models\Applicant;
use App\Models\Portal;
use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentChangeOfCourseImport implements ToCollection
{
    public $faculty, $department, $programme, $option;

    public function __construct($faculty, $department, $programme, $option)
    {
        $this->faculty = $faculty;
        $this->department = $department;
        $this->programme = $programme;
        $this->option = $option;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $count = 0;
        $prog_type = auth()->user()->prog_type_id;
        foreach ($collection as $row) {
            $appno = $row[1];
            $form_no = "";
            if ($appno) {
                $student = Student::whereMatricNo($appno)->orWhere('matset', $appno);
                if ($prog_type) $student->where('stdprogrammetype_id', $prog_type);
                $student = $student->first();
                if ($student) {
                    $form_no = $student->matset == 0 ? $student->matric_no : $student->matset;
                    $student->update([
                        'stdfaculty_id' => $this->faculty,
                        'stddepartment_id' => $this->department,
                        'stdcourse' => $this->option,
                        'stdprogramme_id' => $this->programme,
                        'stdlevel' => $this->programme == 1 ? 1 : 3
                    ]);
                }
                $application_profile = Applicant::whereAppNo($appno)->orWhere('app_no', $form_no);
                if ($prog_type) $application_profile->where('stdprogramme_type', $prog_type);
                if ($application_profile = $application_profile->first()) {
                    $application_profile->update([
                        'fac_id' => $this->faculty,
                        'dept_id' => $this->department,
                        'stdcourse' => $this->option,
                        'stdprogramme_id' => $this->programme,
                    ]);
                }
                $portal = Portal::whereAppno($form_no)->orWhere('appno', $appno);
                if ($prog_type) $portal->where('progtype', $prog_type);
                if ($portal = $portal->first()) {
                    $portal->update([
                        'dept_id'   =>  $this->department,
                        'dcos'      =>  $this->option,
                        'school'    =>  $this->faculty,
                        'prog'      =>  $this->programme,
                        'level'     =>  $this->programme == 1 ? 1 : 3
                    ]);
                }
                $count++;
            }
        }

        if ($count) $count--;

        return session()->flash('success_alert', "$count Uploads Successful");
    }
}
