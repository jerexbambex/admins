<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    use HasFactory;

    protected $primaryKey = 'thecourse_id';

    public $timestamps = false;

    protected $appends = ['level_text'];

    protected $fillable = [
        'thecourse_title', 'thecourse_unit', 'thecourse_code',
        'semester', 'thecourse_cat', 'for_set', 'for_cec',
        'theschool_id', 'levels', 'stdcourse', 'department_id'
    ];

    // public function department_courses()
    // {
    //     return $this->belongsToMany(Department::class, 'dept_options', 'do_id', 'do_id', 'thecourse_id', 'departments_id');
    // }

    public function department_course()
    {
        return $this->belongsTo(DeptOption::class, 'stdcourse', 'do_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'departments_id');
    }
    // public function lecturer()
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function isAssigned($set = '', $session = '')
    {
        $set = $set ? $set : session()->get('app_session');
        $session = $session ? $session : session()->get('sch_session');
        $session_year = explode('/', $session)[0];
        return (bool) LecturerCourse::join('courses', 'courses.thecourse_id', 'lecturer_courses.course_id')
            ->whereCourseId($this->thecourse_id)->whereSessionYear($session_year)
            ->whereRaw("courses.for_set like '%$set%'")->count() > 0;
    }

    public function getLevelTextAttribute()
    {
        if (isset($this->original['levels'])) {
            $level_id = $this->original['levels'];
            return isset(LEVELS[$level_id]) ? LEVELS[$level_id] : '';
        }
        return '';
    }

    public function getForSetAttribute($value)
    {
        return explode(',', $value);
    }

    public function setForSetAttribute($value)
    {
        $this->attributes['for_set'] = implode(',', $value);
    }

    public function totalStudents($set_year = 2020, $prog_type = 1): int
    {
        $course_id = $this->thecourse_id;
        return DB::table('stdprofile')->selectRaw("DISTINCT stdprofile.*")
            ->join('courses', 'courses.stdcourse', 'stdprofile.stdcourse')
            ->where('courses.for_set', 'like', "%$set_year%")
            ->where('courses.thecourse_id', $course_id)
            ->where('stdprofile.std_admyear', $set_year)
            ->whereRaw("stdprofile.stdprogrammetype_id = $prog_type")
            ->count();
        // ->toSql();
    }

    public function totalPaidStudents($set_year = 2020, $session_year = 2020, $prog_type = 1): int
    {
        $course_id = $this->thecourse_id;
        return DB::table('stdprofile')->selectRaw("DISTINCT stdtransaction.log_id")
            ->join('courses', 'courses.stdcourse', 'stdprofile.stdcourse')
            ->join('stdtransaction', 'stdtransaction.log_id', 'stdprofile.std_logid')
            ->where('courses.for_set', 'like', "%$set_year%")
            ->where('courses.thecourse_id', $course_id)
            ->where('stdprofile.std_admyear', $set_year)
            ->where('stdtransaction.pay_status', 'paid')
            ->where('stdtransaction.trans_name', 'like', '%school%')
            ->where('stdtransaction.trans_year', $session_year)
            ->whereRaw('stdtransaction.trans_semester = courses.semester')
            ->whereRaw("stdprofile.stdprogrammetype_id = $prog_type")
            ->count();
    }

    public function registeredStudents($set_year = 2020, $session_year = 2020, $prog_type = 1): int
    {
        $course_id = $this->thecourse_id;
        return DB::table('stdprofile')->selectRaw("DISTINCT stdprofile.*")->join('courses', 'courses.stdcourse', 'stdprofile.stdcourse')
            ->join('course_reg', 'course_reg.thecourse_id', 'courses.thecourse_id')
            ->whereRaw("courses.for_set like '%$set_year%'")
            ->whereRaw('course_reg.csemester = courses.semester')
            ->whereRaw('course_reg.log_id = stdprofile.std_logid')
            ->whereRaw("courses.thecourse_id = $course_id")
            ->whereRaw("stdprofile.std_admyear = $set_year")
            ->whereRaw("stdprofile.stdprogrammetype_id = $prog_type")
            ->whereRaw("course_reg.cyearsession = $session_year")
            ->count();
    }
}
