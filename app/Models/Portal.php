<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Portal extends Model
{
    use HasFactory;

    protected $table = 'portalaccess';

    protected $primaryKey = 'pid';

    public $timestamps = false;

    protected $fillable = [
        'pid', 'appno', 'fullname', 'dcos', 'dept_id',
        'gender', 'school', 'state',
        'prog', 'progtype', 'level',
        'stdtype', 'adm_year',
    ];

    protected $appends = [
        'department_name', 'programme_name',
        'programme_type_name', 'state_name',
        'faculty_name', 'level_name'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id', 'departments_id');
    }

    public function course()
    {
        return $this->belongsTo(DeptOption::class, 'dcos', 'do_id');
    }

    public function getDepartmentNameAttribute()
    {
        $dept = $this->department()->first();
        return $dept ? $dept->departments_name : '';
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class, 'prog', 'programme_id');
    }

    public function getProgrammeNameAttribute()
    {
        $prog = $this->programme()->first();
        return $prog ? $prog->programme_name : '';
    }

    public function progType()
    {
        return $this->belongsTo(ProgrammeType::class, 'progtype', 'programmet_id');
    }

    public function getProgrammeTypeNameAttribute()
    {
        $prog = $this->progType()->first();
        return $prog ? $prog->programmet_name : '';
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state', 'state_id');
    }

    public function getStateNameAttribute()
    {
        $state = $this->state()->first();
        return $state ? $state->state_name : '';
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'school', 'faculties_id');
    }

    public function getFacultyNameAttribute()
    {
        $fac = $this->faculty()->first();
        return $fac ? $fac->faculties_name : '';
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level', 'level_id');
    }

    public function getLevelNameAttribute()
    {
        $lvl = $this->level()->first();
        return $lvl ? $lvl->level_name : '';
    }

    public function hasDuplicates()
    {
        return Portal::whereAppno($this->appno)->count() > 1;
    }

    function profile()
    {
        return Student::whereMatset($this->appno)->orWhere('matric_no', $this->appno);
    }

    function profile_login()
    {
        return StdLogin::whereLogFormNumber($this->appno)->orWhere('log_username', $this->appno);
    }

    function sessions()
    {
        return StudentSession::whereFormNumber($this->appno)->orWhere('matric_number', $this->appno);
    }

    function prog_type_change_logs(): MorphMany
    {
        return $this->morphMany(ProgrammeTypeChangeLog::class, 'changeable');
    }
}
