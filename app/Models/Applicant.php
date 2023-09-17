<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Applicant extends Model
{
    use HasFactory;

    protected $primaryKey = 'std_id';

    public $timestamps = false;

    protected $table = 'application_profile';

    protected $fillable = [
        'std_logid', 'app_no', 'surname', 'firstname',
        'othernames', 'state_of_origin',
        'stdcourse', 'stdprogramme_id',
        'std_programmetype',  'gender', 'std_id', 'exam_date',
        'biodata', 'std_custome8', 'std_custome9',
        'std_custome5', 'std_custome6', 'std_custome7'
    ];

    protected $appends = [
        'full_name', 'reg_no', 'faculty_name', 'department_name',
        'course_name', 'programme_name', 'programme_type_name',
        'submit_status', 'admit_status',
        'modified_date_admitted'
    ];

    public function app_login()
    {
        return $this->belongsTo(AppLogin::class, 'std_logid', 'log_id');
    }

    public function admission_data()
    {
        return $this->hasOne(Portal::class, 'appno', 'app_no');
    }

    public function dept_option()
    {
        return $this->belongsTo(DeptOption::class, 'stdcourse', 'do_id');
    }

    public function getCourseNameAttribute()
    {
        // return $this->dept_option ? $this->dept_option->programme_option : '';
        $option = $this->dept_option()->first();
        return $option ? $option->programme_option : '';
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'fac_id', 'faculties_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id', 'departments_id');
    }

    public function getDepartmentNameAttribute()
    {
        // return $this->department ? $this->department->departments_name : '';
        $dept = $this->department()->first();
        return $dept ? $dept->departments_name : '';
    }

    public function getFacultyNameAttribute()
    {
        // return $this->department ? $this->department->departments_name : '';
        $fac = $this->faculty()->first();
        return $fac ? $fac->faculties_name : '';
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class, 'stdprogramme_id', 'programme_id');
    }

    public function getProgrammeNameAttribute()
    {
        $programme = $this->programme()->first();
        return $programme ? $programme->aprogramme_name : '';
    }

    public function progType()
    {
        return $this->belongsTo(ProgrammeType::class, 'std_programmetype', 'programmet_id');
    }

    public function getProgrammeTypeNameAttribute()
    {
        // return $this->progType ? $this->progType->programmet_name : '';
        $progType = $this->progType()->first();
        return $progType ? $progType->programmet_name : '';
    }

    public function getFullNameAttribute()
    {
        return "$this->surname, $this->firstname $this->othernames";
    }

    public function student_profile()
    {
        return Student::whereMatricNo($this->app_no)->orwhere('matset', $this->app_no)->first();
    }

    // public function getJambsScoreAttribute()
    // {
    //     return $this->jambs()->sum('jscore');
    // }

    public function jambs()
    {
        return $this->hasMany(Jamb::class, 'std_id', 'std_logid');
    }

    public function olevels()
    {
        return $this->hasMany(Olevel::class, 'std_id', 'std_logid');
    }

    // public function getOlevelsStringAttribute()
    // {
    //     $olevels = $this->olevels()->get();
    //     return \App\Services\ApplicantService::getApplicantOLevelsString($olevels);
    // }

    public function getSubmitStatusAttribute()
    {
        $status = $this->std_custome9 ? $this->std_custome9 : 0;
        return SUBMIT_STATUS[$status];
    }

    public function getAdmitStatusAttribute()
    {
        $status = $this->adm_status ? $this->adm_status : 0;
        return ADM_STATUS[$status];
    }

    public function getRegNoAttribute()
    {
        $jamb_detail = $this->jamb_detail()->first();
        return $jamb_detail ? $jamb_detail->jambno : $this->app_no;
    }

    public function getModifiedDateAdmittedAttribute()
    {
        if (!$this->date_admitted) return "No Date";
        return gmdate('jS F, Y', strtotime($this->date_admitted));
    }

    public function jamb_detail()
    {
        return $this->hasOne(Jamb::class, 'std_id', 'std_logid');
    }
}
