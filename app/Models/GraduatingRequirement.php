<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GraduatingRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_year',
        'dept_option_id',
        'core',
        'elective',
        'gs'
    ];

    function session()
    {
        return $this->belongsTo(SchoolSession::class, 'admission_year', 'year');
    }

    function dept_option()
    {
        return $this->belongsTo(DeptOption::class, 'dept_option_id', 'do_id');
    }
}
