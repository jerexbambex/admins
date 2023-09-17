<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPenalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'log_id', 'session', 'semester_id', 'level_id', 'std_no',
        'penalty', 'description', 'user_id',
        'reinstated_to', 'reinstated_by', 'reinstated_at',
        'date_penalized'
    ];

    protected $appends = ['matric_number', 'assigned_by', 'reinstated_user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reinstate_user()
    {
        return $this->belongsTo(User::class, 'reinstated_by', 'id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'level_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'log_id', 'std_logid');
    }

    public function getMatricNumberAttribute()
    {
        $student = $this->student()->first(['matric_no']);
        if ($student) return $student->matric_no;
        return $this->std_no;
    }

    public function getAssignedByAttribute()
    {
        $user = $this->user()->first(['title', 'name']);
        if ($user) return $user->full_name;
        return "";
    }

    public function getReinstatedUserAttribute()
    {
        $user = $this->reinstate_user()->first(['title', 'name']);
        if ($user) return $user->full_name;
        return "";
    }
}
