<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'stdtransaction';

    protected $primaryKey = 'trans_id';

    public $timestamps = false;

    protected $appends = [
        'full_name', 'department_name', 
        'programme_name', 'programme_type_name', 
        'level_name', 'state_name', 'faculty_name'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'user_dept', 'departments_id');
    }

    public function getDepartmentNameAttribute()
    {
        $dept = $this->department()->first();
        return $dept ? $dept->departments_name : '';
    }

    public function programme(){
        return $this->belongsTo(Programme::class, 'prog_id', 'programme_id');
    }

    public function getProgrammeNameAttribute()
    {
        $programme = $this->programme()->first();
        return $programme ? $programme->aprogramme_name : '';
    }
    
    public function level(){
        return $this->belongsTo(Level::class, 'levelid', 'level_id');
    }

    public function getLevelNameAttribute()
    {
        $lvl = $this->level()->first();
        return $lvl ? $lvl->level_name : '';
    }

    public function progType(){
        return $this->belongsTo(ProgrammeType::class, 'prog_type', 'programmet_id');
    }

    public function getProgrammeTypeNameAttribute()
    {
        $progType = $this->progType()->first();
        return $progType ? $progType->programmet_name : '';
    }

    public function faculty(){
        return $this->belongsTo(Faculty::class, 'user_faculty', 'faculties_id');
    }

    public function getFacultyNameAttribute()
    {
        $fac = $this->faculty()->first();
        return $fac ? $fac->faculties_name : '';
    }

    public function student(){
        return $this->belongsTo(Student::class, 'log_id', 'std_logid');
    }
    
    public function getFullNameAttribute()
    {
        $student = $this->student()->first();
        return $student ? $student->full_name : '';
    }

    public function state(){
        return $this->belongsTo(State::class, 'appsor', 'state_id');
    }

    public function getStateNameAttribute()
    {
        $state = $this->state()->first();
        return $state ? $state->state_name : '';
    }

    public function trans_session()
    {
        return $this->belongsTo(SchoolSession::class, 'trans_year', 'year');
    }
}
