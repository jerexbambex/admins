<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class LecturerCourse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['lecturer_id', 'course_id', 'programme_type_id', 'session_year', 'assigned_by'];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function hod()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'thecourse_id');
    }

    public function programmeType()
    {
        return $this->belongsTo(ProgrammeType::class, 'programme_type_id', 'programmet_id');
    }

    public function session()
    {
        return $this->belongsTo(SchSession::class, 'session_year', 'year');
    }

    public function totalResultsSubmitted($set_year = 2020, $prog_type = 1)
    {
        return DB::table('student_results')->selectRaw("DISTINCT stdprofile.*")->join('stdprofile', 'stdprofile.std_logid', 'student_results.log_id')
        ->join('courses', 'courses.thecourse_id', 'student_results.course_id')
        ->join('course_reg', 'course_reg.thecourse_id', 'courses.thecourse_id')
        ->where('courses.for_set', 'like', "%$set_year%")
        ->whereRaw('course_reg.csemester = courses.semester')
        ->whereRaw('course_reg.log_id = stdprofile.std_logid')
        ->where('stdprofile.std_admyear', $set_year)
        ->whereRaw("stdprofile.stdprogrammetype_id = $prog_type")
        ->where('course_reg.cyearsession', $this->session_year)
        ->where('student_results.course_id', $this->course_id)
        ->count();
    }
}
