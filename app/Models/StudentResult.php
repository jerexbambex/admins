<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'log_id', 'course_code', 'session',
        'matric_number', 'c_a', 'mid_semester',
        'examination', 'total', 'grade',
        'semester', 'level_id', 'prog_type_id', 'course_id',
        'lecturer_id', 'hod_id', 'dean_id', 'rector_id',
        'presentation', 'lecturer_course_id', 'bos_approved',
        'date_approved', 'status', 'hod_editable', 'lecturer_editable', 
        'bos_number'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'log_id', 'std_logid');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'thecourse_id');
    }

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id', 'id');
    }

    public function lecturer_course()
    {
        return $this->belongsTo(LecturerCourse::class);
    }

    public function hod()
    {
        return $this->belongsTo(User::class, 'hod_id', 'id');
    }

    public function dean()
    {
        return $this->belongsTo(User::class, 'dean_id', 'id');
    }

    public function rector()
    {
        return $this->belongsTo(User::class, 'rector_id', 'id');
    }
}
