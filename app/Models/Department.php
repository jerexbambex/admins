<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $primaryKey = 'departments_id';

    protected $appends = ['cur_session'];

    public function courses()
    {
        // return $this->hasManyThrough(Course::class, DeptOption::class, 'do_id', 'stdcourse', 'departments_id', 'dept_id');
        return $this->hasMany(Course::class, 'department_id', 'departments_id');
    }

    public function getCurSessionAttribute()
    {
        return SchoolSession::latest()->first()->year;
    }

    public function department_options()
    {
        return $this->hasMany(DeptOption::class, 'dept_id', 'departments_id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'fac_id', 'faculties_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'stddepartment_id', 'departments_id');
    }

    public function hod()
    {
        return User::join('user_roles', 'users.id', 'user_roles.user_id')
        ->where('user_roles.role_id', Role::whereName('hod')->first(['id'])->id)
        ->where('users.department_id', $this->departments_id)->latest('user_roles.created_at')->first();
    }

    public function students_base()
    {
        if(!auth()->user()) return $this->students();
        
        if(!auth()->user()->prog_type_id) return $this->students();

        return $this->students()->where('stdprogrammetype_id', auth()->user()->prog_type_id);
    }

    public function total_cleared() : int
    {
        return $this->students_base()->whereStdAdmyear($this->cur_session)->whereEclearance(1)->count();
    }

    public function uncleared() : int
    {
        return $this->students_base()->whereStdAdmyear($this->cur_session)->whereEclearance(0)->count();
    }

    public function cleared_today() : int
    {
        $today_array = [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')];
        return $this->students_base()->whereStdAdmyear($this->cur_session)->whereEclearance(1)->whereBetween('date_cleared', $today_array)->count();
    }

    public function clearance_status()
    {
        // if(!$this->uncleared()) return 'bg-success';
        // if($this->cleared_today() < 20) return 'bg-danger';
        // if($this->uncleared() > $this->total_cleared()) return 'bg-warning';
        return 'bg-primary';
    }
}
