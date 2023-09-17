<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultEditPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'lecturer_course_id', 'date_from', 'date_to', 
        'approved', 'approved_by'
    ];

    public function lecturer_course()
    {
        return $this->belongsTo(LecturerCourse::class, 'lecturer_course_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}
