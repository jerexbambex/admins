<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeptOption extends Model
{
    use HasFactory;

    protected $primaryKey = 'do_id';

    protected $appends = ['cur_session'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id', 'departments_id');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class, 'prog_id', 'programme_id');
    }

    public function getCurSessionAttribute()
    {
        return SchoolSession::latest()->first()->year;
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'stdcourse', 'do_id');
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
