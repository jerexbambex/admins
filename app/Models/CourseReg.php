<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseReg extends Model
{
    use HasFactory;

    protected $table = 'course_reg';

    protected $primaryKey = 'stdcourse_id';

    public $timestamps = false;

    function student()
    {
        return $this->belongsTo(Student::class, 'log_id', 'std_logid');
    }
}
