<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    protected $table = "stdprofile";

    protected $primaryKey = 'std_id';

    public $timestamps = false;

    protected $fillable = [
        'std_logid', 'matric_no', 'surname', 'firstname',
        'othernames', 'state_of_origin', 'stdfaculty_id',
        'stddepartment_id', 'stdcourse', 'stdprogramme_id',
        'stdlevel', 'stdprogrammetype_id', 'matset', 'gender',
        'std_id', 'birthdate', 'status'
    ];

    protected $appends = [
        'full_name', 'course_name', 'department_name',
        'programme_name', 'programme_type_name',
        'level_name', 'state_name', 'faculty_name',
        'lga_name', 'submit_status', 'clearance_status',
        'modified_date_cleared', 'jamb_no', 'student_photo_url'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'stddepartment_id', 'departments_id');
    }

    public function getDepartmentNameAttribute()
    {
        $dept = $this->department()->first();
        return $dept ? $dept->departments_name : '';
    }

    public function course()
    {
        return $this->belongsTo(DeptOption::class, 'stdcourse', 'do_id');
    }

    public function getCourseNameAttribute()
    {
        $option = $this->course()->first();
        return $option ? $option->programme_option : '';
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

    public function level()
    {
        return $this->belongsTo(Level::class, 'stdlevel', 'level_id');
    }

    public function getLevelNameAttribute()
    {
        $lvl = $this->level()->first();
        return $lvl ? $lvl->level_name : '';
    }

    public function progType()
    {
        return $this->belongsTo(ProgrammeType::class, 'stdprogrammetype_id', 'programmet_id');
    }

    public function getProgrammeTypeNameAttribute()
    {
        $progType = $this->progType()->first();
        return $progType ? $progType->programmet_name : '';
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_of_origin', 'state_id');
    }

    public function getStateNameAttribute()
    {
        $state = $this->state()->first();
        return $state ? $state->state_name : '';
    }

    public function lga()
    {
        return $this->belongsTo(Lga::class, 'local_gov', 'state_id');
    }

    public function getLgaNameAttribute()
    {
        $lga = $this->lga()->first();
        return $lga ? $lga->lga_name : '';
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'stdfaculty_id', 'faculties_id');
    }

    public function penalties()
    {
        return $this->hasMany(StudentPenalty::class, 'log_id', 'std_logid');
    }

    public function hasPenalty($type = 'expel', $session = '2020/2021', $semester = 1, $level = 1): bool
    {
        $hasPenalty = $this->penalties()->where(function ($q) use ($session) {
            $q->whereSession($session)->orWhereNull('session');
        })->where(function ($q) use ($semester) {
            $q->whereSemesterId($semester)->orWhereNull('semester_id');
        })->where(function ($q) use ($level) {
            $q->whereLevelId($level)->orWhereNull('level_id');
        })->wherePenalty($type)->where(function ($q) {
            $q->where(function ($q2) {
                $q2->whereNull('reinstated_to')->whereNull('reinstated_by')->whereNull('reinstated_at');
            })->orWhere(function ($q2) {
                $q2->whereNotNull('reinstated_to')->whereNotNull('reinstated_by')->whereNotNull('reinstated_at');
            });
        })->count() >= 1;

        $penalty_type = '';
        switch ($type) {
            case 'expel':
                $penalty_type = 'expelled';
                break;

            case 'suspend':
                $penalty_type = 'suspended';
                break;

            default:
                break;
        }
        $is_penalized = false;
        if ($penalty_type) $this->status === $penalty_type;
        return $hasPenalty or $is_penalized;
    }

    public function getFacultyNameAttribute()
    {
        $fac = $this->faculty()->first();
        return $fac ? $fac->faculties_name : '';
    }


    public function getFullNameAttribute()
    {
        return "$this->surname, $this->firstname $this->othernames";
    }

    public function getSubmitStatusAttribute()
    {
        return SUBMIT_STATUS[$this->cs_status];
    }

    public function getClearanceStatusAttribute()
    {
        return $this->eclearance == 1 ? 'Cleared' : 'Not Cleared';
    }

    public function getModifiedDateClearedAttribute()
    {
        if (!$this->date_cleared) return NULL;
        return gmdate('jS F, Y', strtotime($this->date_cleared));
    }

    public function getJambNoAttribute()
    {
        $app = $this->applicant_profile();
        if (!$app) return null;
        $jamb = null;
        if ($app) $jamb = $app->jamb_detail()->first();
        if ($jamb) return $jamb->jambno;
        return $app->app_no;
    }

    public function getStudentPhotoUrlAttribute()
    {
        return env('UPLOAD_PATH') . $this->std_photo;
    }


    public function course_reg()
    {
        return $this->hasMany(CourseReg::class, 'log_id', 'std_logid');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'log_id', 'std_logid');
    }

    public function results()
    {
        return $this->hasMany(StudentResult::class, 'log_id', 'std_logid');
    }

    public function sessions()
    {
        return $this->hasMany(StudentSession::class, 'log_id', 'std_logid');
    }

    public function login()
    {
        return $this->belongsTo(StdLogin::class, 'std_logid', 'log_id');
    }

    public function hasCourseReg(String $session = '2020', Int $sem = 1, $level = '1')
    {
        return (bool) $this->course_reg()->where('cyearsession', $session)->whereCsemester(SEMESTERS[$sem])->where('clevel_id', $level)->count();
    }

    public function hasPayment(String $session_year = '2020', Int $sem = 1, $level = '1')
    {
        $semester = SEMESTERS[$sem];
        return (bool) $this->transactions()
            ->where('trans_year', $session_year)
            ->whereRaw("trans_semester like '%$semester%'")
            ->where('levelid', $level)
            ->whereRaw("(trans_name like '%school%' or trans_name like '%tuition%')")
            ->where('pay_status', 'paid')
            ->count() > 0;
    }

    public function hasResult(String $session, Int $course_id)
    {
        return $this->results()->whereSession($session)->whereCourseId($course_id)->count() && 
        $this->course_reg()->whereCyearsession(explode('/', $session)[0])->whereThecourseId($course_id)->count();
    }

    public function result(String $session, Int $course_id)
    {
        return $this->results()->whereSession($session)->whereCourseId($course_id)->first();
    }

    public function courses_registered($session, $semester, $level, $is_cumulative = false)
    {
        $session_year = explode('/', $session)[0];
        $data = $this->course_reg();
        // if (!in_array($level, [1, 3])) {

        // Get previous semester data - extra query
        $prevSessions = [];
        $num_sem = $this->stdprogrammetype_id == 2 ? 3 : 2;
        $sems = range(1, $num_sem);
        $sems = array_map("mapSemesters", $sems);
        if ($is_cumulative) {
            $prevSessions = \App\Models\SchoolSession::selectRaw('DISTINCT school_sessions.year')
                ->where('year', '<', $session_year)->orderBy('year', 'desc')
                ->join('student_sessions', function ($join) {
                    $join->on('student_sessions.session', '=', 'school_sessions.session')
                        ->where('student_sessions.log_id', $this->std_logid);
                })
                ->get();
        }

        // current session data - extra query
        $data->where(function ($q) use ($session_year, $level, $semester, $is_cumulative, $prevSessions, $sems) {

            //first query - current session
            $q->where(function ($q2) use ($session_year, $level, $semester, $is_cumulative) {
                $semesters = [];
                if ($semester) {
                    $semesters = [$semester];
                    if ($is_cumulative) {
                        $semesters = range(1, $semester);
                    }
                } else {
                    $num_sem = $this->stdprogrammetype_id == 2 ? 3 : 2;
                    $semesters = range(1, $num_sem);
                }

                $semesters = array_map("mapSemesters", $semesters);
                $semesters_string = "";
                foreach ($semesters as $key => $sem) {
                    if (!$key) $semesters_string .= sprintf("'%s'", $sem);
                    else $semesters_string .= sprintf(",'%s'", $sem);
                }

                if ($is_cumulative) {
                    $q2->whereRaw("cyearsession = '$session_year'")->whereRaw("clevel_id <= $level");
                } else {
                    $q2->whereRaw("cyearsession = '$session_year'")->whereRaw("clevel_id = $level");
                }

                if ($semester)
                    $q2->whereRaw("csemester in ($semesters_string)");
                else $q2->whereRaw("csemester not in ($semesters_string)");
            });

            // previous sessions query
            if ($prevSessions) {
                $q->orWhere(function ($q2) use ($prevSessions, $sems) {
                    foreach ($prevSessions as $key => $sess) {
                        if (!$key) {
                            $q2->where(function ($query) use ($sems, $sess) {
                                $query->whereCyearsession($sess->year)->whereIn('csemester', $sems)->where('clevel_id', '<=', $this->stdprogramme_id == 1 ? 2 : 4);
                            });
                        } else {
                            $q2->orWhere(function ($query) use ($sems, $sess) {
                                $query->whereCyearsession($sess->year)->whereIn('csemester', $sems)->where('clevel_id', '<=', $this->stdprogramme_id == 1 ? 2 : 4);
                            });
                        }
                    }
                });
            }
        });



        return $data;
    }

    public function get_courses_codes($session, $semester, $level)
    {
        $codes = [];
        foreach ($this->courses_registered($session, $semester, $level)->get(['c_code']) as $course) $codes[] = $course->c_code;
        return $codes;
    }

    public function get_courses_units($session, $semester, $level, ?string $type = '', ?bool $is_cumulative = false)
    {
        $query = $this->courses_registered($session, $semester, $level, $is_cumulative);
        $query->join('courses', 'courses.thecourse_id', 'course_reg.thecourse_id');
        if ($type) $query->where('thecourse_cat', $type);
        return $query->sum('thecourse_unit');
    }

    public function get_passed_courses_units($session, $semester, $level, ?string $type = '', ?bool $is_cumulative = false)
    {
        // $sessions = "(";
        // if (!in_array($level, [1, 3])) {
        // Previous sessions data - extra query
        // $prevSessions = \App\Models\SchoolSession::where('year', '<', explode('/', $session)[0])->orderBy('year', 'desc')
        //     ->join('student_sessions', function ($join) {
        //         $join->on('student_sessions.session', '=', 'school_sessions.session')
        //             ->where('student_sessions.log_id', $this->std_logid);
        //     })
        //     ->get(['school_sessions.session']);
        // foreach ($prevSessions as $key => $sess) {
        //     if (!$key) $sessions .= $sess->session;
        //     else $session .= ",$sess->session";
        // }
        // // }
        // $sessions .= ")";

        // Previous sessions semester and levels
        // $levels = "()";
        // $semesters_string = "(";
        // if ($sessions != "()") {

        //     switch ($this->stdprogramme_id) {
        //         case '1':
        //             $levels = "(1,2)";
        //             break;

        //         case '2':
        //             $levels = "(3,4)";
        //             break;

        //         default:
        //             break;
        //     }

        //     $semesters = range(1, $this->stdprogrammetype_id == 2 ? 3 : 2);
        //     $semesters = array_map("mapSemesters", $semesters);
        //     foreach ($semesters as $key => $sem) {
        //         if (!$key) $semesters_string .= sprintf("'%s'", $sem);
        //         else $semesters_string .= sprintf(",'%s'", $sem);
        //     }
        // }
        // $semesters_string .= ")";


        // normal session semester query
        // $normal_semesters_string = "(";
        // if ($semester) {
        //     $semesters = range(1, $semester);
        //     $semesters = array_map("mapSemesters", $semesters);
        //     foreach ($semesters as $key => $sem) {
        //         if (!$key) $normal_semesters_string .= sprintf("'%s'", $sem);
        //         else $normal_semesters_string .= sprintf(",'%s'", $sem);
        //     }
        // }
        // $normal_semesters_string .= ")";

        // concat extra queries
        // $extra_sql = "";
        // if ($sessions != "()") {
        //     $extra_sql = "((session in $sessions and semester in $semesters_string and level_id in $levels) or (session = '$session' and semester in $normal_semesters_string and level_id <= $level))";
        // } else {
        //     $extra_sql = "session = '$session' and semester in $normal_semesters_string and level_id <= $level";
        // }

        // dd(compact('extra_sql', 'semester', 'normal_semesters_string', 'semesters'));

        $query = $this->courses_registered($session, $semester, $level, $is_cumulative);
        $query->whereRaw("course_reg.thecourse_id in (SELECT course_id from student_results where log_id = $this->std_logid and grade != 'F')");
        $query->join('courses', 'courses.thecourse_id', 'course_reg.thecourse_id');
        if ($type) $query->where('thecourse_cat', $type);
        return $query->sum('courses.thecourse_unit');
    }

    public function result_query($session, $semester, $level, ?bool $is_cumulative = false)
    {
        $sess_year = explode('/', $session)[0];
        // $semesters = [];
        // $semesters_string = "";
        // if ($semester) {
        //     $semesters = range(1, $semester);
        //     $semesters = array_map("mapSemesters", $semesters);
        //     foreach ($semesters as $key => $sem) {
        //         if (!$key) $semesters_string .= sprintf("'%s'", $sem);
        //         else $semesters_string .= sprintf(",'%s'", $sem);
        //     }
        // }

        // Extra query
        // $q = "student_results.course_id in (SELECT thecourse_id from course_reg where log_id = $this->std_logid and clevel_id = $level and cyearsession = $sess_year ";
        // if ($semesters_string) $q .= " and csemester in ($semesters_string)";
        // $q .= ")";

        $data = $this->results()->whereRaw("student_results.course_id in (SELECT thecourse_id from course_reg where log_id = $this->std_logid)");


        //Previous session data
        $prevSessions = [];
        $num_sem = $this->stdprogrammetype_id == 2 ? 3 : 2;
        $sems = range(1, $num_sem);
        if ($is_cumulative) {
            $prevSessions = \App\Models\SchoolSession::selectRaw('DISTINCT school_sessions.session')
                ->where('year', '<', $sess_year)->orderBy('year', 'desc')
                ->join('student_sessions', function ($join) {
                    $join->on('student_sessions.session', '=', 'school_sessions.session')
                        ->where('student_sessions.log_id', $this->std_logid);
                })
                ->get();
        }


        $data->where(function ($query) use ($session, $level, $semester, $is_cumulative, $prevSessions, $sems) {

            // Current session query
            $query->where(function ($q) use ($session, $level, $semester, $is_cumulative) {
                $semesters = [];
                if ($semester) {
                    $semesters = [$semester];
                    if ($is_cumulative) $semesters = range(1, $semester);
                } else {
                    $num_sem = $this->stdprogrammetype_id == 2 ? 3 : 2;
                    $semesters = range(1, $num_sem);
                }

                $q->where('student_results.session', $session);
                if ($is_cumulative) {
                    $q->where('student_results.level_id', '<=', $level);
                } else {
                    $q->where('student_results.level_id', '=', $level);
                }

                if ($semester) {
                    $q->whereIn('student_results.semester', $semesters);
                } else {
                    $q->whereNotIn('student_results.semester', $semesters);
                }
            });

            // Previous sessions query
            if ($prevSessions) {
                $query->orWhere(function ($q) use ($prevSessions, $sems) {
                    foreach ($prevSessions as $key => $sess) {
                        if (!$key) {
                            $q->where(function ($q2) use ($sess, $sems) {
                                $q2->where('student_results.session', $sess->session)->where('student_results.level_id', '<=', $this->stdprogramme_id == 1 ? 2 : 4)
                                    ->whereIn('student_results.semester', $sems);
                            });
                        } else {
                            $q->orWhere(function ($q2) use ($sess, $sems) {
                                $q2->where('student_results.session', $sess->session)->where('student_results.level_id', '<=', $this->stdprogramme_id == 1 ? 2 : 4)
                                    ->whereIn('student_results.semester', $sems);
                            });
                        }
                    }
                });
            }
        });
        // dd($data->toSql());
        return $data;
    }

    public function get_result_points($session, $semester, $level, ?bool $is_cumulative = false)
    {
        $query = $this->result_query($session, $semester, $level, $is_cumulative);
        $sum = 0;
        foreach ($query->get() as $result) $sum += (float)(POINTS[grade($result->total)] * $result->course->thecourse_unit);
        return (float)$sum;
    }

    public function get_result_gp($session, $semester, $level, ?bool $is_cumulative = false)
    {
        $points = $this->get_result_points($session, $semester, $level, $is_cumulative);
        $units = $this->get_courses_units($session, $semester, $level, '', $is_cumulative);
        if (!$units) return NULL;
        $gp = $points / $units;
        $gp = (float) number_format($gp, 2);
        return $gp;
    }

    public function rerun_lists($session, $semester, $level, ?bool $is_cumulative = false)
    {
        return $this->result_query($session, $semester, $level, $is_cumulative)->where('grade', 'F');
        // return $this->result_query($session, $semester, $level, $is_cumulative)->where('total', '<', 40);
    }

    public function hasEM($session, $semester, $level): bool
    {
        return $this->result_query($session, $semester, $level)->where('status', 'em')->count() > 0;
    }

    public function isAbsent($session, $semester, $level): bool
    {
        return $this->result_query($session, $semester, $level)->where('status', 'abs')->count() > 0;
    }

    public function carry_overs($session, $semester_id, $level, ?bool $is_cumulative = false)
    {
        if ($semester_id) $semester = SEMESTERS[$semester_id];
        $list = $this->rerun_lists($session, $semester_id, $level, $is_cumulative)
            ->join('courses', 'courses.thecourse_id', 'student_results.course_id');
        // if ($semester_id) {
        //     if (!$is_cumulative) $list->whereRaw("courses.semester = '$semester'");
        //     else $list->whereRaw("courses.semester != '$semester'");
        // }
        return $list->where('thecourse_cat', '!=', 'C')->get();
    }

    public function carry_forwards($session, $semester_id, $level, ?bool $is_cumulative = false)
    {
        if ($semester_id) $semester = SEMESTERS[$semester_id];
        $list = $this->rerun_lists($session, $semester_id, $level, $is_cumulative)
            ->join('courses', 'courses.thecourse_id', 'student_results.course_id');
        // if ($semester_id) {
        //     if (!$is_cumulative) $list->whereRaw("courses.semester = '$semester'");
        //     else $list->whereRaw("courses.semester != '$semester'");
        // }
        return $list->where('thecourse_cat', 'C')->get();
    }

    function hasAwaiting($session, $semester, $level)
    {
        return count($this->awaiting_list($session, $semester, $level)->toArray()) > 0;
    }

    public function remarks($session, $set, $semester, $level, $graduating = false, $is_summary = false): string
    {
        $total_course_reg = $this->courses_registered($session, $semester, $level, true)->count();
        $total_result = $this->result_query($session, $semester, $level, true)->count();
        // dd($total_course_reg, $total_result);
        $reruns = $this->result_query($session, $semester, $level, true)
            ->join('courses', 'courses.thecourse_id', 'student_results.course_id')
            ->where('grade', 'F');

        $gp = $this->get_result_gp($session, $semester, $level, true);
        if ($total_course_reg && $total_course_reg > $total_result) return 'AWR';

        $hasAwaiting = $this->hasAwaiting($session, $semester, $level);
        $hasOutstanding = count($this->outstanding_courses_list($session, $set, $semester, $level, 'C')->toArray()) > 0;

        if ($hasOutstanding) {
            if ($is_summary) return 'CSO';
            return 'CF';
        }

        if ($hasAwaiting) return 'CSO';

        if ($graduating) {
            // if ($reruns_core) {
            //     if ($is_summary) return 'CSO';
            //     return 'CF';
            // }
            // if ($reruns_others) {
            if ($is_summary) return 'CSO';
            return 'CO';
            // }
            return grade_points($gp);
        } else {
            if ($reruns->count() > 0) {
                if ($semester > 1) {
                    // if ($reruns_core) {
                    //     if ($is_summary) return 'CSO';
                    //     return 'CF';
                    // }
                    // if ($reruns_others) {
                    if ($is_summary) return 'CSO';
                    return 'CO';
                    // }
                }
                return 'CSO';
            }
            if ((float)$gp < floatval(1.5) && $semester == 1) return 'WARNING';
            if ((float)$gp < floatval(2.0) && $semester > 1) return 'WITHDRAW';
        }

        return 'PASS';
    }

    public function awaiting_list($session, $semester, $level)
    {
        return $this->courses_registered($session, $semester, $level)
            ->join('courses', 'courses.thecourse_id', 'course_reg.thecourse_id')
            ->selectRaw('DISTINCT courses.thecourse_code')
            ->whereRaw("course_reg.thecourse_id not in (SELECT course_id from student_results where session = '$session' and log_id = $this->std_logid and level_id = $level and semester = $semester and deleted_at is null)")
            ->where('course_reg.cyearsession', explode('/', $session)[0])
            ->where('course_reg.csemester', SEMESTERS[$semester])
            ->where('course_reg.clevel_id', $level)
            ->get();
    }

    public function outstanding_courses_list($session, $set, $semester, $level, $status)
    {
        $sess_year = explode('/', $session)[0];
        // dd(func_get_args(), $this->std_logid);
        // $semester_string = SEMESTERS[$semester];
        $data = DB::table('courses')->select('thecourse_code')
            ->whereRaw("thecourse_id not in (SELECT thecourse_id from course_reg where log_id = $this->std_logid)")
            // ->whereRaw("thecourse_id not in (SELECT thecourse_id from course_reg where cyearsession = $sess_year and csemester = '$semester_string' and clevel_id = $level)")
            ->where("stdcourse", "$this->stdcourse")
            ->whereRaw("thecourse_cat = '$status'")
            ->whereRaw("for_set like '%$set%'");

        $prevSessions = \App\Models\SchoolSession::selectRaw('DISTINCT student_sessions.level_id')
            ->where('year', '<', $sess_year)->orderBy('year', 'desc')
            ->join('student_sessions', function ($join) {
                $join->on('student_sessions.session', '=', 'school_sessions.session')
                    ->where('student_sessions.log_id', $this->std_logid);
            })
            ->get();

        // if (($level != 1 and $level != 3)) {

        // }

        $data->where(function ($query) use ($level, $semester, $prevSessions) {
            $query->where(function ($q) use ($level, $semester) {
                $semesters = [];
                if ($semester) {
                    $semesters = range(1, $semester);
                } else {
                    $num_sem = $this->stdprogrammetype_id == 2 ? 3 : 2;
                    $semesters = range(1, $num_sem);
                }

                $semesters = array_map("mapSemesters", $semesters);
                $semesters_string = "";
                foreach ($semesters as $key => $sem) {
                    if (!$key) $semesters_string .= sprintf("'%s'", $sem);
                    else $semesters_string .= sprintf(",'%s'", $sem);
                }
                $q->whereRaw("levels <= $level");
                // if ($semester) 
                $q->whereRaw("semester in ($semesters_string)");
                // else $q->whereRaw("csemester not in ($semesters_string)");
            });

            if ($prevSessions) {
                $num_sem = $this->stdprogrammetype_id == 2 ? 3 : 2;
                $sems = range(1, $num_sem);
                $sems = array_map("mapSemesters", $sems);
                $query->orwhere(function ($q) use ($prevSessions, $sems, $level) {
                    foreach ($prevSessions as $key => $sess) {
                        if (!$key) {
                            $q->where(function ($q2) use ($sems, $sess) {
                                $q2->whereIn('semester', $sems)->where('levels', '<=', $sess->level_id);
                            });
                        } else {
                            $q->orWhere(function ($q2) use ($sems, $sess) {
                                $q2->whereIn('semester', $sems)->where('levels', '<=', $sess->level_id);
                            });
                        }
                    }
                });
            }
        });
        $for_cec = $this->stdprogrammetype_id == 2 ? 1 : 0;
        return $data->whereForCec($for_cec)->get();
    }

    public function awaiting_list_string($session, $semester, $level): string
    {
        $course_reg = $this->awaiting_list($session, $semester, $level)->toArray();
        $course_reg_arr = [];
        foreach ($course_reg as $reg) $course_reg_arr[] = $reg['thecourse_code'];
        if (count($course_reg_arr))
            return implode("(AWR), ", $course_reg_arr) . "(AWR), ";
        else return "";
    }

    public function outstanding_courses_list_string($session, $set, $semester, $level, $status): string
    {
        $course_reg = $this->outstanding_courses_list($session, $set, $semester, $level, $status);
        $course_reg_arr = [];
        foreach ($course_reg as $reg) $course_reg_arr[] = $reg->thecourse_code;
        $status_text = $status == 'C' ? 'CF' : 'CO';
        if (count($course_reg_arr))
            return implode("($status_text), ", $course_reg_arr) . "($status_text), ";
        else return "";
    }

    public function applicant_profile()
    {
        return Applicant::whereAppNo($this->matric_no)->orWhere('app_no', $this->matset)->first();
    }

    public function eclearance_files()
    {
        return $this->hasMany(Eclearance::class, 'std_id', 'std_logid');
    }

    function graduatingRequirements()
    {
        return $this->hasOne(GraduatingRequirement::class, 'admission_year', 'std_admyear');
    }

    function graduatingRequirement()
    {
        return $this->graduatingRequirements()->whereDeptOptionId($this->stdcourse)->first();
    }

    function canGraduate($session, $semester, $level): bool
    {
        if ($graduating_requirement = $this->graduatingRequirement()) {
            $total_core = $this->get_passed_courses_units($session, $semester, $level, 'C', true);
            $total_elective = $this->get_passed_courses_units($session, $semester, $level, 'E', true);
            $total_gs = $this->get_passed_courses_units($session, $semester, $level, 'G', true);

            return ($total_core >= $graduating_requirement->core &&
                $total_elective >= $graduating_requirement->elective &&
                $total_gs >= $graduating_requirement->gs
            );
        }
        return false;
    }
}
