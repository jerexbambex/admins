<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'session', 'admission_year',
        'log_id', 'form_number', 'matric_number', 
        'prog_id', 'prog_type_id', 'level_id',
        'course_form', 'semester', 
        'payment'
    ];

    public function student_data()
    {
        return $this->belongsTo(Student::class, 'log_id', 'std_logid');
    }
}
